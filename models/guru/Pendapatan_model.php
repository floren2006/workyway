<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendapatan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Hitung total pendapatan berdasarkan guru_id
     */
    
    public function get_total_pendapatan($guru_id) {
        $this->db->select('SUM(t.gaji) as total');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('t.status', 'success');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['total'] ? (float)$result['total'] : 0;
    }

    /**
     * Hitung komisi bulan ini berdasarkan guru_id
     */
    public function get_komisi_bulan_ini($guru_id) {
        $bulan_ini = date('Y-m');
        $this->db->select('SUM(t.gaji) as total');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('t.status', 'success');
        $this->db->where("DATE_FORMAT(t.tanggal_transaksi, '%Y-%m') = ", $bulan_ini);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['total'] ? (float)$result['total'] : 0;
    }

    /**
     * Hitung pendapatan pending berdasarkan guru_id
     */
    public function get_pending_gaji($guru_id) {
        $this->db->select('SUM(t.gaji) as total');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('t.status', 'pending');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['total'] ? (float)$result['total'] : 0;
    }

    /**
     * Ambil riwayat transaksi berdasarkan guru_id
     */
    public function get_riwayat_transaksi($guru_id, $limit = 20) {
        $this->db->select('
            t.transaksi_id,
            t.tanggal_transaksi,
            t.jumlah,
            t.gaji,
            t.status,
            t.metode_pembayaran,
            k.judul_kursus,
            u.nama as nama_siswa
        ');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->join('siswa s', 's.siswa_id = e.siswa_id');
        $this->db->join('users u', 'u.user_id = s.user_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->order_by('t.tanggal_transaksi', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Ambil statistik tambahan untuk dashboard
     */
    public function get_statistik_tambahan($guru_id) {
        $data = [];
        
        // Jumlah kursus aktif
        $this->db->select('COUNT(*) as total_kursus');
        $this->db->from('kursus');
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'active');
        $query = $this->db->get();
        $result = $query->row_array();
        $data['total_kursus'] = $result['total_kursus'] ?? 0;
        
        // Jumlah siswa terdaftar
        $this->db->select('COUNT(DISTINCT e.siswa_id) as total_siswa');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data['total_siswa'] = $result['total_siswa'] ?? 0;
        
        return $data;
    }
}