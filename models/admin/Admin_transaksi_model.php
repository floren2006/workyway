<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_transaksi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get total transaction statistics
    public function get_total_transaksi_stats() {
        $this->db->select('
            SUM(CASE WHEN t.status = "success" THEN t.jumlah ELSE 0 END) as total_transaksi,
            SUM(CASE WHEN t.status = "success" THEN (t.jumlah * 0.10) ELSE 0 END) as total_komisi,
            SUM(CASE WHEN t.status = "success" AND k.lpk_id IS NOT NULL THEN (t.jumlah * 0.80) ELSE 0 END) as pembayaran_lpk,
            SUM(CASE WHEN t.status = "success" AND k.guru_id IS NOT NULL THEN (t.jumlah * 0.10) ELSE 0 END) as pembayaran_guru
        ');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 'e.enrollment_id = t.enrollment_id');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        
        return $this->db->get()->row();
    }

    // Get all transactions with pagination
    public function get_all_transaksi($limit = 10, $start = 0, $search = '') {
        $this->db->select('
            t.transaksi_id,
            t.tanggal_transaksi,
            t.jumlah,
            t.metode_pembayaran,
            t.status,
            u.nama as siswa_nama,
            k.judul_kursus,
            (t.jumlah * 0.10) as komisi_platform
        ');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 'e.enrollment_id = t.enrollment_id');
        $this->db->join('siswa s', 's.siswa_id = e.siswa_id');
        $this->db->join('users u', 'u.user_id = s.user_id');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('u.nama', $search);
            $this->db->or_like('k.judul_kursus', $search);
            $this->db->or_like('CONCAT("TRX", LPAD(t.transaksi_id, 3, "0"))', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('t.tanggal_transaksi', 'DESC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    // Count total transactions for pagination
    public function count_all_transaksi($search = '') {
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 'e.enrollment_id = t.enrollment_id');
        $this->db->join('siswa s', 's.siswa_id = e.siswa_id');
        $this->db->join('users u', 'u.user_id = s.user_id');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('u.nama', $search);
            $this->db->or_like('k.judul_kursus', $search);
            $this->db->or_like('CONCAT("TRX", LPAD(t.transaksi_id, 3, "0"))', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    // Update transaction status
    public function update_status($id, $status) {
        $data = array(
            'status' => $status,
            'tanggal_transaksi' => date('Y-m-d H:i:s')
        );
        $this->db->where('transaksi_id', $id);
        return $this->db->update('transaksi', $data);
    }

    // Get transaction by ID
    public function get_transaksi_by_id($id) {
        $this->db->select('
            t.*,
            u.nama as siswa_nama,
            k.judul_kursus,
            (t.jumlah * 0.10) as komisi_platform
        ');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 'e.enrollment_id = t.enrollment_id');
        $this->db->join('siswa s', 's.siswa_id = e.siswa_id');
        $this->db->join('users u', 'u.user_id = s.user_id');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->where('t.transaksi_id', $id);
        
        return $this->db->get()->row();
    }
}
?>