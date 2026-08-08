<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_laporan extends CI_Controller {
    
    private $admin_id = 1; // Admin dengan ID 1
    private $model_name;
    
    public function __construct() {
        parent::__construct();
        
        // Load required libraries and helpers
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        
        // Dua opsi untuk memuat model (pilih salah satu):
        
        // Opsi 1: Memuat model dengan nama "admin_laporan_model" (huruf kecil)
        $this->load->model('admin/admin_manajemen_laporan', 'laporan_model');
        
        // Opsi 2: Atau jika file model bernama "Admin_manajemen_laporan.php"
        // $this->load->model('admin/Admin_manajemen_laporan', 'laporan_model');
        
        // Check if user is accessing from valid admin account
        $this->check_admin_access();
    }
    
    private function check_admin_access() {
        // Get current user from URL or default to admin ID 1
        $current_user = $this->input->get('admin_id') ?: $this->admin_id;
        
        // Check if user exists and is admin
        $this->db->where('user_id', $current_user);
        $this->db->where('role', 'admin');
        $admin = $this->db->get('users')->row();
        
        if (!$admin) {
            show_error('Akses ditolak. Hanya admin yang dapat mengakses halaman ini.', 403);
        }
        
        // Set admin data for view
        $this->admin_data = $admin;
    }
    
    public function index() {
        $data['title'] = 'Laporan & Statistik';
        $data['active_menu'] = 'laporan';
        $data['admin_name'] = $this->admin_data->nama;
        
        try {
            // Get statistics menggunakan model dengan nama alias "laporan_model"
            $data['overview_stats'] = $this->laporan_model->get_overview_stats();
            $data['monthly_growth'] = $this->laporan_model->get_monthly_growth();
            $data['top_instructors'] = $this->laporan_model->get_top_instructors();
            $data['category_performance'] = $this->laporan_model->get_category_performance();
            $data['general_stats'] = $this->laporan_model->get_general_stats();
            
            // Format numbers for display
            if (isset($data['overview_stats']['pendapatan_total'])) {
                $data['formatted_revenue'] = $this->format_revenue($data['overview_stats']['pendapatan_total']);
            } else {
                $data['formatted_revenue'] = 'Rp 0';
            }
            
        } catch (Exception $e) {
            // Jika ada error, set data default
            $data['overview_stats'] = array(
                'pengguna_aktif' => 0,
                'kursus_populer' => 0,
                'pendapatan_total' => 0,
                'rating_platform' => 0,
                'pertumbuhan_pengguna' => '0%',
                'pertumbuhan_kursus' => '0%',
                'pertumbuhan_pendapatan' => '0%',
                'pertumbuhan_rating' => '+0.0'
            );
            $data['monthly_growth'] = array();
            $data['top_instructors'] = array();
            $data['category_performance'] = array();
            $data['general_stats'] = array();
            $data['formatted_revenue'] = 'Rp 0';
        }
        
        // Load view
        $this->load->view('admin/laporan', $data);
    }
    
    public function download() {
        $this->laporan_model->generate_csv_report();
    }
    
    private function format_revenue($amount) {
        if ($amount >= 1000000000) {
            return round($amount / 1000000000, 1) . 'B';
        } elseif ($amount >= 1000000) {
            return round($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return round($amount / 1000, 1) . 'K';
        }
        return number_format($amount, 0, ',', '.');
    }
}