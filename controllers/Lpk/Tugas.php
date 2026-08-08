<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tugas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // WAJIB load session
        $this->load->library('session');
        
        // WAJIB load database - TAMBAHKAN INI
        $this->load->database();

        $this->load->model('lpk/Tugas_model', 'tugas');
        $this->load->model('lpk/Materi_model');
        $this->load->library('form_validation');

        // CEK LOGIN LPK
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'lpk') {
            redirect('auth');
        }
    }

    public function index()
    {
        $lpk_id = $this->session->userdata('lpk_id');
        
        // Ambil data tugas
        $data['tugas'] = $this->tugas->getByLpk($lpk_id);
        
        // Ambil semua kursus milik LPK untuk dropdown kursus
        $data['kursus'] = $this->getKursusByLpk($lpk_id);
        
        // Default: ambil materi dari kursus pertama (atau kosong)
        $data['materi'] = [];
        if (!empty($data['kursus'])) {
            $kursus_id = $data['kursus'][0]['kursus_id'] ?? null;
            if ($kursus_id) {
                $data['materi'] = $this->Materi_model->getByKursus($kursus_id);
            }
        }
        
        $this->load->view('lpk/sidebar');
        $this->load->view('lpk/buat_tugas_view', $data);
    }

    public function create()
    {
        $lpk_id = $this->session->userdata('lpk_id');
        
        // Ambil semua kursus milik LPK
        $data['kursus'] = $this->getKursusByLpk($lpk_id);
        
        // Default: ambil materi dari kursus pertama (atau kosong)
        $data['materi'] = [];
        if (!empty($data['kursus'])) {
            $kursus_id = $data['kursus'][0]['kursus_id'] ?? null;
            if ($kursus_id) {
                $data['materi'] = $this->Materi_model->getByKursus($kursus_id);
            }
        }
        
        $this->load->view('lpk/sidebar');
        $this->load->view('lpk/buat_tugas_view', $data);
    }

    // Tambahkan method untuk mengambil kursus berdasarkan LPK
    private function getKursusByLpk($lpk_id)
    {
        $this->db->select('kursus_id, judul_kursus');
        $this->db->from('kursus');
        $this->db->where('lpk_id', $lpk_id);
        $this->db->order_by('judul_kursus', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /* =========================
        STORE
    ========================= */
   public function store()
    {
        $this->form_validation->set_rules('judul_tugas', 'Judul Tugas', 'required|max_length[255]');
        $this->form_validation->set_rules('materi_id', 'Materi', 'required|numeric');
        $this->form_validation->set_rules('tipe_tugas', 'Tipe Tugas', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('deadline', 'Deadline', 'required');
        $this->form_validation->set_rules(
            'max_score',
            'Nilai Maksimal',
            'required|numeric|greater_than[0]|less_than_equal_to[100]'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        /* =========================
        UPLOAD FILE TEMPLATE (OPSIONAL)
        ========================= */
        $file_template = null;

        if (!empty($_FILES['file_template']['name'])) {
            $config = [
                'upload_path'   => './uploads/template_tugas/',
                'allowed_types' => 'zip|pdf|doc|docx',
                'max_size'      => 51200, // 50MB
                'encrypt_name'  => TRUE
            ];

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_template')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                $this->create();
                return;
            }

            $file_template = $this->upload->data('file_name');
        }

        /* =========================
        INSERT DATABASE
        ========================= */
        $data = [
            'materi_id'     => $this->input->post('materi_id', true),
            'judul_tugas'   => $this->input->post('judul_tugas', true),
            'deskripsi'     => $this->input->post('deskripsi', true),
            'deadline'      => $this->input->post('deadline', true),
            'tipe_tugas'    => $this->input->post('tipe_tugas', true),
            'max_score'     => $this->input->post('max_score', true),
            'file_template' => $file_template,
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tugas', $data);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Tugas berhasil ditambahkan');
            redirect('lpk/tugas');
        }

        show_error('Gagal menyimpan tugas');
    }


    /* =========================
    AJAX: GET MATERI BY KURSUS
    ========================= */
    public function get_materi_by_kursus($kursus_id)
    {
        // Cek apakah kursus ini milik LPK yang login
        $lpk_id = $this->session->userdata('lpk_id');
        
        $this->db->select('kursus_id, lpk_id');
        $this->db->from('kursus');
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('lpk_id', $lpk_id);
        $kursus = $this->db->get()->row_array();
        
        if (!$kursus) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kursus tidak ditemukan atau tidak memiliki akses'
            ]);
            return;
        }
        
        // Ambil materi dari kursus ini
        $this->load->model('lpk/Materi_model');
        $materi = $this->Materi_model->getByKursus($kursus_id);
        
        echo json_encode([
            'status' => 'success',
            'materi' => $materi
        ]);
    }

    /* =========================
        EDIT
    ========================= */
    public function edit($id)
    {
        $data['tugas'] = $this->db->get_where('tugas', ['tugas_id'=>$id])->row_array();
        if (!$data['tugas']) redirect('lpk/tugas');

        $lpk_id = $this->session->userdata('lpk_id');
        $data['materi'] = $this->Materi_model->getByLpk($lpk_id);

        $data['tipe_tugas_options'] = [
            'individual' => 'Individual',
            'kelompok'   => 'Kelompok'
        ];

        $this->load->view('lpk/sidebar');
        $this->load->view('lpk/tugas/edit_view', $data);
    }

    /* =========================
        UPDATE
    ========================= */
    public function update($id)
    {
        $tugas = $this->db->get_where('tugas',['tugas_id'=>$id])->row_array();
        if (!$tugas) redirect('lpk/tugas');

        $file_template = $tugas['file_template'];

        if (!empty($_FILES['file_template']['name'])) {
            if ($file_template && file_exists('./uploads/template_tugas/'.$file_template)) {
                unlink('./uploads/template_tugas/'.$file_template);
            }

            $config = [
                'upload_path'   => './uploads/template_tugas/',
                'allowed_types' => 'zip|rar|pdf|doc|docx',
                'max_size'      => 51200,
                'encrypt_name'  => TRUE
            ];

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('file_template')) {
                $file_template = $this->upload->data('file_name');
            }
        }

        $data = [
            'materi_id'   => $this->input->post('materi_id'),
            'judul_tugas' => $this->input->post('judul_tugas'),
            'deskripsi'   => $this->input->post('deskripsi'),
            'deadline'    => $this->input->post('deadline'),
            'tipe_tugas'  => $this->input->post('tipe_tugas'),
            'max_score'   => $this->input->post('max_score'),
            'file_template' => $file_template
        ];

        $this->db->where('tugas_id',$id)->update('tugas',$data);

        $this->session->set_flashdata('success','Tugas berhasil diperbarui');
        redirect('lpk/tugas');
    }

    /* =========================
        DELETE
    ========================= */
    public function delete($id)
    {
        $this->db->delete('tugas', ['tugas_id'=>$id]);
        $this->session->set_flashdata('success','Tugas berhasil dihapus');
        redirect('lpk/tugas');
    }
}
