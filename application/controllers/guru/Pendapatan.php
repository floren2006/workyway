<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendapatan extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('guru/Pendapatan_model');
        $this->load->library('session');
        $this->load->database();

        // CEK LOGIN
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // CEK ROLE
        if ($this->session->userdata('role') !== 'guru') {
            show_error('Akses ditolak');
        }
    }

    public function index() {
        $this->pendapatan();
    }

    public function pendapatan() {
        $guru_id = $this->session->userdata('guru_id');
        $user_id = $this->session->userdata('user_id');

        
        // Ambil data guru dari database berdasarkan guru_id = 1
        $this->load->database();
        $this->db->select('guru_freelance.*, users.nama, users.email');
        $this->db->from('guru_freelance');
        $this->db->join('users', 'users.user_id = guru_freelance.user_id');
        $this->db->where('guru_freelance.guru_id', $guru_id);
        $guru_query = $this->db->get();
        $data['guru'] = $guru_query->row_array();
        
        $data['title'] = 'Pendapatan Guru';
        $data['guru_id'] = $guru_id;
        
        // Hitung statistik pendapatan
        $data['total_pendapatan'] = $this->Pendapatan_model->get_total_pendapatan($guru_id);
        $data['komisi_bulan_ini'] = $this->Pendapatan_model->get_komisi_bulan_ini($guru_id);
        $data['pending'] = $this->Pendapatan_model->get_pending_gaji($guru_id);
        
        // Ambil riwayat transaksi
        $data['transaksi'] = $this->Pendapatan_model->get_riwayat_transaksi($guru_id);
        
        // Load view
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/pendapatan', $data);
        $this->load->view('templates/guru_footer');
    }
}