<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Check database connection
    public function check_database() {
        return $this->db->conn_id !== FALSE;
    }

    // Get admin data
    public function get_admin_data() {
        $this->db->where('user_id', 1);
        $query = $this->db->get('users');
        return $query->row_array();
    }

    // Get total statistics
    public function get_total_stats() {
        $stats = array();
        
        // Count total siswa
        $this->db->where('role', 'siswa');
        $stats['total_siswa'] = $this->db->count_all_results('users');
        
        // Count total guru
        $this->db->where('role', 'guru');
        $stats['total_guru'] = $this->db->count_all_results('users');
        
        // Count total LPK
        $this->db->where('role', 'lpk');
        $stats['total_lpk'] = $this->db->count_all_results('users');
        
        // Count total transaksi sukses
        $this->db->where('status', 'success');
        $stats['total_transaksi'] = $this->db->count_all_results('transaksi');
        
        // Total pendapatan
        $this->db->select_sum('jumlah');
        $this->db->where('status', 'success');
        $query = $this->db->get('transaksi');
        $stats['total_pendapatan'] = $query->row()->jumlah ?: 0;
        
        // Count kursus aktif
        $this->db->where('status_kursus', 'active');
        $stats['kursus_aktif'] = $this->db->count_all_results('kursus');
        
        // Count kursus pending
        $this->db->where('status_kursus', 'pending');
        $stats['kursus_pending'] = $this->db->count_all_results('kursus');
        
        // Count total enrollment
        $stats['total_enrollment'] = $this->db->count_all_results('enrollment');
        
        // Average rating
        $this->db->select_avg('rating');
        $this->db->where('rating IS NOT NULL');
        $query = $this->db->get('enrollment');
        $stats['avg_rating'] = $query->row()->rating ?: 0;
        
        return $stats;
    }

    // Get monthly statistics
    public function get_monthly_stats() {
        $current_month = date('m');
        $current_year = date('Y');
        
        $stats = array();
        
        // Pendapatan bulan ini
        $this->db->select_sum('jumlah');
        $this->db->where('status', 'success');
        $this->db->where('MONTH(tanggal_transaksi)', $current_month);
        $this->db->where('YEAR(tanggal_transaksi)', $current_year);
        $query = $this->db->get('transaksi');
        $stats['pendapatan_bulan_ini'] = $query->row()->jumlah ?: 0;
        
        // Transaksi bulan ini
        $this->db->where('status', 'success');
        $this->db->where('MONTH(tanggal_transaksi)', $current_month);
        $this->db->where('YEAR(tanggal_transaksi)', $current_year);
        $stats['transaksi_bulan_ini'] = $this->db->count_all_results('transaksi');
        
        return $stats;
    }
}