<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// LOAD MIDTRANS MANUAL
require_once APPPATH . '../midtrans-php/Midtrans.php';

class Midtrans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('siswa/Pendaftaran_model');

        // CONFIG MIDTRANS (SIMULASI)
        \Midtrans\Config::$serverKey = 'Mid-server-NHtRWY_ic9rqjs2_D8LFR7Iz';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function process() {

        // SIMULASI USER LOGIN
        $user_id   = 1;

        $kursus_id = $this->input->post('kursus_id');
        $total     = $this->input->post('total_amount');

        // 1️⃣ BUAT ENROLLMENT + TRANSAKSI (PENDING)
        $order_id = 'ORDER-' . time();

        $enrollment_id = $this->Pendaftaran_model
            ->create_enrollment_pending($user_id, $kursus_id, $total, $order_id);

        if (!$enrollment_id) {
            show_error('Gagal membuat enrollment', 500);
        }

        // 2️⃣ PARAMETER MIDTRANS
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => (int)$total
            ],
            'customer_details' => [
                'first_name' => $this->input->post('customer_name'),
                'email'      => $this->input->post('customer_email'),
                'phone'      => $this->input->post('customer_phone')
            ]
        ];

        // 3️⃣ SNAP TOKEN
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $data = [
            'snapToken'    => $snapToken,
            'enrollment_id'=> $enrollment_id,
            'total'         => $total
        ];

        $this->load->view('siswa/midtrans_snap', $data);
    }

    // SIMULASI SUCCESS
public function success($enrollment_id)
{
    // ambil transaksi terakhir berdasarkan enrollment
    $transaksi = $this->db
        ->where('enrollment_id', $enrollment_id)
        ->order_by('transaksi_id', 'DESC')
        ->get('transaksi')
        ->row();

    if (!$transaksi) {
        show_error('Transaksi tidak ditemukan');
    }

    // update hanya status (metode SUDAH DISET SEBELUMNYA)
    $this->db->where('enrollment_id', $enrollment_id)
             ->update('transaksi', [
                 'status' => 'success'
             ]);

    redirect('siswa/dashboard');
}
public function update_metode()
{
    $data = json_decode(file_get_contents("php://input"), true);

    $this->db->where('enrollment_id', $data['enrollment_id'])
             ->update('transaksi', [
                 'metode_pembayaran' => $data['metode']
             ]);
}





}
