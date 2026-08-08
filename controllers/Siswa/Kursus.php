<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kursus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('siswa/Kursus_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        if ($this->session->userdata('role') !== 'siswa') {
            show_error('Akses ditolak', 403);
        }

        $this->user_id = $this->session->userdata('user_id');
    }

    public function index() {
        // Ambil siswa_id dari session
        $user_id = $this->session->userdata('user_id');
        $siswa = $this->db->get_where('siswa', ['user_id' => $user_id])->row();
        $siswa_id = $siswa ? $siswa->siswa_id : null;
        
        // Ambil parameter filter dari GET
        $filter_kategori = $this->input->get('kategori');
        $filter_kesulitan = $this->input->get('kesulitan');
        $filter_harga = $this->input->get('harga');
        $search = $this->input->get('search');
        
        // Ambil data kursus dengan filter dan eksklusi kursus yang sudah didaftar
        $data['kursus_list'] = $this->Kursus_model->get_all_kursus(
            $filter_kategori,
            $filter_kesulitan,
            $filter_harga,
            $search,
            $siswa_id
        );
        
        $data['total_kursus'] = count($data['kursus_list']);
        $data['kategori_list'] = $this->Kursus_model->get_kategori_list();
        $data['filter_kategori'] = $filter_kategori;
        $data['filter_kesulitan'] = $filter_kesulitan;
        $data['filter_harga'] = $filter_harga;
        $data['search'] = $search;
        
        $data['siswa'] = $this->Kursus_model->get_user_data($this->user_id);
        $data['siswa_id'] = $siswa_id;

        $this->load->view('siswa/kursus/index', $data);
    }

    

    
}