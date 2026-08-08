<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session'); 
        $this->load->model('siswa/Dashboard_model');

        // Cek login
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'siswa') {
            redirect('login');
        }
        
        $this->user_id = $this->session->userdata('user_id');
    }

    public function index() {
        $user_id = $this->user_id;

        $siswa = $this->db->get_where('siswa', [
            'user_id' => $user_id
        ])->row();

        if (!$siswa) {
            show_error('Data siswa tidak ditemukan');
        }

        $siswa_id = $siswa->siswa_id;

        $data['siswa'] = $this->Dashboard_model->get_siswa_data($user_id);
        
        // Dapatkan kursus aktif dengan progress
        $data['kursus_dashboard'] = $this->Dashboard_model->get_kursus_dengan_progress($user_id);
        
        // Hitung statistik
        $data['kursus_aktif'] = count($data['kursus_dashboard']);
        $data['sertifikat'] = $this->Dashboard_model->get_jumlah_sertifikat($user_id);
        
        // Hitung progress rata-rata
        $total_progress = 0;
        $count_kursus = count($data['kursus_dashboard']);
        foreach ($data['kursus_dashboard'] as $kursus) {
            $total_progress += $kursus->progress;
        }
        $data['progress_rata'] = $count_kursus > 0 ? round($total_progress / $count_kursus) : 0;
        
        $data['rekomendasi_kursus'] = $this->Dashboard_model->get_rekomendasi_kursus($user_id);
        $data['notifikasi'] = $this->Dashboard_model->get_notifikasi($user_id);

        $this->load->view('siswa/dashboard/index', $data);
    }
    
    

    
}