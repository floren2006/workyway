<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi extends Guru_Base_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->materi();
    }

    public function materi($kursus_id = null) {
        $guru_id = $this->guru_id;
        $data['guru'] = $this->guru_data;
        $data['title'] = 'Materi Kursus';
        
        if ($kursus_id) {
            // Cek kepemilikan kursus
            $kursus = $this->guru_model->get_kursus_by_id($kursus_id);
            if (!$kursus || $kursus['guru_id'] != $guru_id) {
                $this->session->set_flashdata('error', 'Akses ditolak atau kursus tidak ditemukan');
                redirect('guru/materi');
            }
            
            $data['materi'] = $this->guru_model->get_materi_by_kursus($kursus_id);
            $data['kursus'] = $kursus;
        } else {
            // Tampilkan daftar kursus
            $data['kursus_list'] = $this->guru_model->get_all_kursus($guru_id);
        }
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/materi', $data);
        $this->load->view('templates/guru_footer');
    }
}