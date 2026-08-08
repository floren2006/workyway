<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session'); 
        $this->load->model('guru/Siswa_model');
    }

    public function index() {
        $this->daftar_siswa();
    }

    public function daftar_siswa() {
        $guru_id = $this->session->userdata('guru_id');

        if (!$guru_id) {
            redirect('login');
        }

        $guru = $this->db->get_where('guru_freelance', [
            'guru_id' => $guru_id
        ])->row();

        if (!$guru) {
            show_error('Data guru tidak ditemukan');
        }

        // Ambil data guru dari database
        $guru_data = $this->get_guru_data($guru_id);
        
        $data['guru'] = $guru_data;
        $data['title'] = 'Daftar Siswa';
        $data['guru_id'] = $guru_id;
        
        // Ambil data siswa berdasarkan kursus yang dihandle guru ini
        $data['siswa'] = $this->Siswa_model->get_daftar_siswa($guru_id);
        
        // Ambil statistik untuk ditampilkan di view
        $data['statistik'] = $this->Siswa_model->get_statistik_siswa($guru_id);
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/daftar_siswa', $data);
        $this->load->view('templates/guru_footer');
    }

    /**
     * Ambil data guru dari database
     */
    private function get_guru_data($guru_id) {
        $this->db->select('g.*, u.nama, u.email, u.foto_profil');
        $this->db->from('guru_freelance g');
        $this->db->join('users u', 'g.user_id = u.user_id');
        $this->db->where('g.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        
        // Jika tidak ditemukan, berikan data default
        if (empty($result)) {
            $result = [
                'guru_id' => $guru_id,
                'nama' => 'Guru',
                'email' => 'guru@email.com',
                'foto_profil' => 'default.jpg',
                'rating_rata2' => 0
            ];
        }
        
        return $result;
    }
}