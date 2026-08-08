<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian extends CI_Controller {

    private $guru_id;

    public function __construct() {
        parent::__construct();
        
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('guru/Penilaian_model');
        $this->load->database();
        
        // Get user_id from session (assuming guru is logged in)
        $user_id = $this->session->userdata('user_id');
        
        // Get guru_id from user_id
        $this->guru_id = $this->Penilaian_model->get_guru_id_by_user_id($user_id);
        
        // If no guru_id found, use default for testing
        if (!$this->guru_id) {
            // For testing, use guru_id 1 (Agus Salim) who teaches course_id 3 (Database Management)
            // Or use guru_id 3 (Bambang) who teaches course_id 5 (Cyber Security) - which has tasks
            $this->guru_id = 3; // Bambang has tasks with submissions
        }
        
        // Get guru data
        $this->guru_data = $this->get_guru_data($this->guru_id);
        
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }
    
    private function get_guru_data($guru_id) {
        $this->db->select('gf.*, u.*');
        $this->db->from('guru_freelance gf');
        $this->db->join('users u', 'gf.user_id = u.user_id');
        $this->db->where('gf.guru_id', $guru_id);
        return $this->db->get()->row();
    }

    public function index() {
        $this->penilaian();
    }

    public function penilaian() {
        $data['guru'] = $this->guru_data;
        $data['title'] = 'Penilaian Tugas';
        
        // Get statistics
        $stats = $this->Penilaian_model->get_tugas_statistics($this->guru_id);
        $data['perlu_dinilai'] = $stats['perlu_dinilai'];
        $data['sudah_dinilai'] = $stats['sudah_dinilai'];
        $data['rata_rata'] = $stats['rata_rata'];
        
        // Get submission data
        $data['penilaian'] = $this->Penilaian_model->get_pengumpulan_tugas($this->guru_id);
        
        // Get courses for filter dropdown
        $data['kursus_list'] = $this->Penilaian_model->get_kursus_by_guru($this->guru_id);
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/penilaian', $data);
        $this->load->view('templates/guru_footer');
    }

    public function detail($pengumpulan_id) {
        $data['pengumpulan'] = $this->Penilaian_model->get_pengumpulan_by_id($pengumpulan_id, $this->guru_id);
        
        // Check if data exists and guru has permission
        if (!$data['pengumpulan']) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menilai tugas ini.');
            redirect('guru/penilaian');
        }
        
        $data['guru'] = $this->guru_data;
        $data['title'] = 'Penilaian Tugas - ' . $data['pengumpulan']['judul_tugas'];
        
        // Calculate late status
        if ($data['pengumpulan']['deadline'] && $data['pengumpulan']['tanggal_kumpul']) {
            $deadline = strtotime($data['pengumpulan']['deadline']);
            $tanggal_kumpul = strtotime($data['pengumpulan']['tanggal_kumpul']);
            $data['terlambat'] = $tanggal_kumpul > $deadline;
            $data['selisih_hari'] = round(($tanggal_kumpul - $deadline) / (60 * 60 * 24));
        } else {
            $data['terlambat'] = false;
            $data['selisih_hari'] = 0;
        }
        
        // Format dates
        $data['deadline_formatted'] = isset($data['pengumpulan']['deadline']) 
            ? date('d M Y', strtotime($data['pengumpulan']['deadline'])) 
            : 'N/A';
        
        $data['tanggal_kumpul_formatted'] = isset($data['pengumpulan']['tanggal_kumpul']) 
            ? date('d M Y H:i', strtotime($data['pengumpulan']['tanggal_kumpul'])) 
            : 'N/A';
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/pemberian_nilai', $data);
        $this->load->view('templates/guru_footer');
    }

    public function simpan_nilai() {
        $this->form_validation->set_rules('pengumpulan_id', 'ID Pengumpulan', 'required|numeric');
        $this->form_validation->set_rules('nilai', 'Nilai', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('feedback', 'Feedback', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $pengumpulan_id = $this->input->post('pengumpulan_id');
            $this->detail($pengumpulan_id);
        } else {
            $pengumpulan_id = $this->input->post('pengumpulan_id');
            $nilai = $this->input->post('nilai');
            $feedback = $this->input->post('feedback');
            $notifikasi = $this->input->post('notifikasi') ? 1 : 0;
            
            $data_update = array(
                'nilai' => $nilai,
                'feedback' => $feedback,
                'status' => 'dinilai',
                'tanggal_penilaian' => date('Y-m-d H:i:s')
            );
            
            $success = $this->Penilaian_model->update_nilai($pengumpulan_id, $data_update, $this->guru_id);
            
            if ($success) {
                if ($notifikasi) {
                    $this->kirim_notifikasi($pengumpulan_id, $nilai);
                }
                
                $this->update_enrollment_nilai($pengumpulan_id, $nilai);
                $this->session->set_flashdata('success', 'Nilai berhasil disimpan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan nilai. Silakan coba lagi.');
            }
            
            redirect('guru/penilaian');
        }
    }
    
    private function kirim_notifikasi($pengumpulan_id, $nilai) {
        $pengumpulan = $this->Penilaian_model->get_pengumpulan_by_id($pengumpulan_id, $this->guru_id);
        
        if ($pengumpulan) {
            $this->db->select('user_id');
            $this->db->from('siswa');
            $this->db->where('siswa_id', $pengumpulan['siswa_id']);
            $student = $this->db->get()->row();
            
            if ($student) {
                $notifikasi_data = array(
                    'penerima_id' => $student->user_id,
                    'judul' => 'Tugas Telah Dinilai',
                    'isi' => 'Tugas "' . $pengumpulan['judul_tugas'] . '" telah dinilai dengan nilai ' . $nilai . '/100',
                    'tanggal' => date('Y-m-d H:i:s'),
                    'status' => 'unread'
                );
                
                $this->db->insert('notifikasi', $notifikasi_data);
            }
        }
    }
    
    private function update_enrollment_nilai($pengumpulan_id, $nilai) {
        $this->db->select('pt.siswa_id, mk.kursus_id');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->where('pt.pengumpulan_id', $pengumpulan_id);
        $submission = $this->db->get()->row();
        
        if ($submission) {
            $this->db->select('AVG(pt.nilai) as rata_nilai');
            $this->db->from('pengumpulan_tugas pt');
            $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
            $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
            $this->db->where('mk.kursus_id', $submission->kursus_id);
            $this->db->where('pt.siswa_id', $submission->siswa_id);
            $this->db->where('pt.status', 'dinilai');
            $average = $this->db->get()->row();
            
            if ($average && $average->rata_nilai) {
                $this->db->where('kursus_id', $submission->kursus_id);
                $this->db->where('siswa_id', $submission->siswa_id);
                $this->db->update('enrollment', array('nilai' => $average->rata_nilai));
            }
        }
    }
    
    public function download_file($pengumpulan_id) {
        $pengumpulan = $this->Penilaian_model->get_pengumpulan_by_id($pengumpulan_id, $this->guru_id);
        
        if (!$pengumpulan || !$pengumpulan['file_tugas']) {
            $this->session->set_flashdata('error', 'File tidak ditemukan.');
            redirect('guru/penilaian');
        }
        
        $file_path = './uploads/tugas/' . $pengumpulan['file_tugas'];
        
        if (file_exists($file_path)) {
            $this->load->helper('download');
            force_download($file_path, NULL);
        } else {
            $this->session->set_flashdata('error', 'File tidak ditemukan di server.');
            redirect('guru/penilaian/detail/' . $pengumpulan_id);
        }
    }
    
    public function filter($kursus_id = null) {
        $data['guru'] = $this->guru_data;
        $data['title'] = 'Penilaian Tugas - Filter';
        
        // Get statistics
        $stats = $this->Penilaian_model->get_tugas_statistics($this->guru_id);
        $data['perlu_dinilai'] = $stats['perlu_dinilai'];
        $data['sudah_dinilai'] = $stats['sudah_dinilai'];
        $data['rata_rata'] = $stats['rata_rata'];
        
        // Get courses for filter
        $data['kursus_list'] = $this->Penilaian_model->get_kursus_by_guru($this->guru_id);
        
        // Get filtered submission data
        if ($kursus_id && $kursus_id > 0) {
            $data['penilaian'] = $this->Penilaian_model->get_tugas_by_kursus_guru($kursus_id, $this->guru_id);
            $data['selected_kursus'] = $kursus_id;
        } else {
            $data['penilaian'] = $this->Penilaian_model->get_pengumpulan_tugas($this->guru_id);
            $data['selected_kursus'] = null;
        }
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/penilaian_filter', $data);
        $this->load->view('templates/guru_footer');
    }
}