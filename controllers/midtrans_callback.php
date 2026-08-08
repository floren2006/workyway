<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans_callback extends CI_Controller {

    public function index() {

        $json = json_decode(file_get_contents("php://input"));

        $order_id = $json->order_id;
        $enrollment_id = explode('-', $order_id)[1];

        $this->db->where('enrollment_id', $enrollment_id);
        $this->db->update('transaksi', [
            'status' => $json->transaction_status,
            'metode_pembayaran' => $json->payment_type
        ]);
    }
}
