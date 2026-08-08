<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('siswa/Kursus_model');
        $this->load->model('siswa/Pendaftaran_model');
        $this->load->library('session');
    }

    public function checkout($kursus_id)
{
    // Ambil user login
    $user_id = $this->session->userdata('user_id');

    // Load model
    $this->load->model('siswa/Pendaftaran_model');

    // 1️⃣ BUAT ENROLLMENT
    $enrollment_id = $this->Pendaftaran_model
        ->create_enrollment($user_id, $kursus_id);

    if (!$enrollment_id) {
        show_error('Gagal membuat enrollment');
    }

    // 2️⃣ Ambil data pendukung
    $kursus     = $this->Pendaftaran_model->get_kursus_by_id($kursus_id);
    $user       = $this->Pendaftaran_model->get_user_by_id($user_id);

    // 3️⃣ HITUNG BIAYA (DI CONTROLLER, BUKAN VIEW)
    $biaya_kursus   = $kursus->biaya;
    $pajak          = $biaya_kursus * 0.10;
    $platform       = 50000;
    $total          = $biaya_kursus + $pajak + $platform;

    // 4️⃣ KIRIM KE VIEW
    $data = [
        'kursus'        => $kursus,
        'siswa'         => $user,
        'enrollment_id' => $enrollment_id,
        'biaya' => (object)[
            'biaya_kursus'   => $biaya_kursus,
            'pajak'          => $pajak,
            'biaya_platform' => $platform,
            'total'          => $total
        ]
    ];

    $this->load->view('siswa/checkout', $data);
}


    public function process_payment() {
        // Untuk sementara, redirect ke dashboard
        redirect('dashboard?success=Pendaftaran berhasil');
    }
}