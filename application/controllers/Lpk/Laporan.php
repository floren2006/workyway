<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->model('lpk/Laporan_model');

        // 🔐 Cek login & role
        $this->_check_auth();
    }

    private function _check_auth()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        if ($this->session->userdata('role') !== 'lpk') {
            $role = $this->session->userdata('role');
            if ($role === 'siswa') {
                redirect('siswa/dashboard');
            } elseif ($role === 'guru') {
                redirect('guru/dashboard');
            } else {
                redirect('login');
            }
        }
    }

    public function index()
    {
        $lpk_id = $this->session->userdata('lpk_id');

        if (!$lpk_id) {
            show_error('LPK ID tidak ditemukan di session');
        }

        $data['summary']   = $this->Laporan_model->get_summary($lpk_id);
        $data['statistik'] = $this->Laporan_model->statistik_per_kursus($lpk_id);
        $data['nilai_growth']  = $this->Laporan_model->get_avg_growth($lpk_id, 'nilai');
        $data['rating_growth'] = $this->Laporan_model->get_avg_growth($lpk_id, 'rating');
        $data['ulasan']    = $this->Laporan_model->ulasan_terbaru($lpk_id);
        $data['peserta_growth'] = $this->Laporan_model->get_total_peserta_growth();

        $this->load->view('lpk/laporan_kursus_view', $data);
    }

    
}
