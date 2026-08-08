<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian_lpk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load helper URL
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->library('session');
        
        // Cek session LPK
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'lpk') {
            redirect('login');
        }
        
        // Load model - perhatikan path
        $this->load->model('lpk/Penilaian_lpk_model', 'penilaian_model');
        
        // Load database
        $this->load->database();
    }

    public function index() {
        $this->penilaian();
    }

    public function penilaian() {
        $lpk_id = $this->session->userdata('lpk_id');
        $data['title'] = 'Penilaian Tugas - LPK';
        
        // Hitung statistik menggunakan Penilaian_model
        $data['perlu_dinilai'] = $this->penilaian_model->count_perlu_dinilai($lpk_id);
        $data['sudah_dinilai'] = $this->penilaian_model->count_sudah_dinilai($lpk_id);
        $data['rata_rata'] = $this->penilaian_model->rata_rata_nilai($lpk_id);
        
        // Ambil data pengumpulan tugas
        $data['penilaian'] = $this->penilaian_model->get_pengumpulan_tugas($lpk_id);
        
        // Load views dengan path yang benar
        $this->load->view('header', $data);
        $this->load->view('lpk/penilaian', $data);
    }

    // Method untuk detail pengumpulan tugas dan form penilaian
    public function detail($pengumpulan_id) {
        $lpk_id = $this->session->userdata('lpk_id');
        
        // Ambil data pengumpulan tugas
        $data['pengumpulan'] = $this->penilaian_model->get_pengumpulan_by_id($pengumpulan_id, $lpk_id);
        
        // Cek apakah data ditemukan
        if (!$data['pengumpulan']) {
            show_404();
        }
        
        $data['title'] = 'Penilaian Tugas - ' . $data['pengumpulan']['judul_tugas'];
        
        // Hitung status keterlambatan
        if ($data['pengumpulan']['deadline'] && $data['pengumpulan']['tanggal_kumpul']) {
            $deadline = strtotime($data['pengumpulan']['deadline']);
            $tanggal_kumpul = strtotime($data['pengumpulan']['tanggal_kumpul']);
            $data['terlambat'] = $tanggal_kumpul > $deadline;
            $data['selisih_hari'] = round(($tanggal_kumpul - $deadline) / (60 * 60 * 24));
        } else {
            $data['terlambat'] = false;
            $data['selisih_hari'] = 0;
        }
        
        // Format tanggal untuk tampilan
        if (isset($data['pengumpulan']['deadline'])) {
            $data['deadline_formatted'] = date('d M Y', strtotime($data['pengumpulan']['deadline']));
        } else {
            $data['deadline_formatted'] = 'N/A';
        }
        
        if (isset($data['pengumpulan']['tanggal_kumpul'])) {
            $data['tanggal_kumpul_formatted'] = date('d M Y', strtotime($data['pengumpulan']['tanggal_kumpul']));
        } else {
            $data['tanggal_kumpul_formatted'] = 'N/A';
        }
        
        $this->load->view('header', $data);
        $this->load->view('lpk/pemberian_nilai', $data);
    }

    // Method untuk menyimpan nilai - DIPERBAIKI
    public function simpan_nilai() {
        $lpk_id = $this->session->userdata('lpk_id');
        
        // Validasi form
        $this->form_validation->set_rules('pengumpulan_id', 'ID Pengumpulan', 'required|numeric');
        $this->form_validation->set_rules('nilai', 'Nilai', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('feedback', 'Feedback', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke halaman detail
            $pengumpulan_id = $this->input->post('pengumpulan_id');
            $this->detail($pengumpulan_id);
        } else {
            // Ambil data dari form
            $pengumpulan_id = $this->input->post('pengumpulan_id');
            $nilai = $this->input->post('nilai');
            $feedback = $this->input->post('feedback');
            $notifikasi = $this->input->post('notifikasi') ? 1 : 0;
            
            // Data untuk update - SESUAIKAN DENGAN MODEL
            $data_update = array(
                'nilai' => $nilai,
                'feedback' => $feedback,
                'status' => 'dinilai',
                'tanggal_penilaian' => date('Y-m-d H:i:s')
            );
            
            // PERBAIKAN: Panggil fungsi yang benar
            $success = $this->penilaian_model->update_nilai($pengumpulan_id, $data_update, $lpk_id);
            
            if ($success) {
                // Kirim notifikasi jika dicentang
                if ($notifikasi) {
                    $this->kirim_notifikasi($pengumpulan_id, $nilai, $lpk_id);
                }
                
                // Set flashdata untuk pesan sukses
                $this->session->set_flashdata('success', 'Nilai berhasil disimpan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan nilai. Silakan coba lagi.');
            }
            
            // Redirect ke halaman penilaian
            redirect('lpk/penilaian');
        }
    }
    
    private function kirim_notifikasi($pengumpulan_id, $nilai, $lpk_id) {
        // Ambil data pengumpulan untuk notifikasi
        $pengumpulan = $this->penilaian_model->get_pengumpulan_by_id($pengumpulan_id, $lpk_id);
        
        if ($pengumpulan) {
            // PERBAIKAN: Ambil user_id siswa untuk notifikasi
            $this->db->select('user_id');
            $this->db->from('siswa');
            $this->db->where('siswa_id', $pengumpulan['siswa_id']);
            $siswa = $this->db->get()->row_array();
            
            if ($siswa) {
                // Data notifikasi
                $notifikasi_data = array(
                    'penerima_id' => $siswa['user_id'],
                    'judul' => 'Tugas Telah Dinilai oleh LPK',
                    'isi' => 'Tugas "' . $pengumpulan['judul_tugas'] . '" telah dinilai oleh LPK dengan nilai ' . $nilai . '/100',
                    'tanggal' => date('Y-m-d H:i:s'),
                    'status' => 'unread'
                );
                
                // Simpan notifikasi ke database
                $this->db->insert('notifikasi', $notifikasi_data);
            }
        }
    }
    
    // Method untuk menampilkan statistik penilaian
    public function statistik() {
        $lpk_id = $this->session->userdata('lpk_id');
        
        $data['title'] = 'Statistik Penilaian - LPK';
        
        // Ambil data statistik per kursus
        $data['statistik_kursus'] = $this->penilaian_model->get_statistik_per_kursus($lpk_id);
        
        // Ambil data statistik per bulan
        $data['statistik_bulan'] = $this->penilaian_model->get_statistik_per_bulan($lpk_id);
        
        $this->load->view('header', $data);
        $this->load->view('lpk/statistik_penilaian', $data);
    }
}