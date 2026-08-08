<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/admin_dashboard_model');
        // Di construct atau method index, tambahkan:
$this->load->helper('url');
    }

    public function index() {
        // Check database connection
        if (!$this->admin_dashboard_model->check_database()) {
            show_error('Database connection failed. Please check your database configuration.', 500);
        }

        // Get admin data
        $data['admin_data'] = $this->admin_dashboard_model->get_admin_data();
        
        if (!$data['admin_data']) {
            show_error('Admin data not found. Please import the database first.', 404);
        }

        // Get all statistics
        $data['total_stats'] = $this->admin_dashboard_model->get_total_stats();
        $data['monthly_stats'] = $this->admin_dashboard_model->get_monthly_stats();
        
        // Format numbers for display
        $data['formatted_total_pendapatan'] = $this->format_rupiah($data['total_stats']['total_pendapatan']);
        $data['formatted_pendapatan_bulan_ini'] = $this->format_rupiah($data['monthly_stats']['pendapatan_bulan_ini']);
        $data['formatted_avg_rating'] = number_format($data['total_stats']['avg_rating'], 1);
        
        // Load view
        $this->load->view('admin/admin_dashboard_view', $data);
    }

    private function format_rupiah($angka) {
        if ($angka >= 1000000000) {
            return 'Rp ' . number_format($angka / 1000000000, 1, ',', '.') . 'M';
        } elseif ($angka >= 1000000) {
            return 'Rp ' . number_format($angka / 1000000, 1, ',', '.') . 'Jt';
        }
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public function refresh_stats() {
        // Set header for JSON response
        header('Content-Type: application/json');
        
        // Get updated stats
        $total_stats = $this->admin_dashboard_model->get_total_stats();
        $monthly_stats = $this->admin_dashboard_model->get_monthly_stats();
        
        // Prepare response
        $response = array(
            'success' => true,
            'data' => array(
                'total_siswa' => $total_stats['total_siswa'],
                'total_guru' => $total_stats['total_guru'],
                'total_lpk' => $total_stats['total_lpk'],
                'total_transaksi' => $total_stats['total_transaksi'],
                'total_pendapatan' => $this->format_rupiah($total_stats['total_pendapatan']),
                'kursus_aktif' => $total_stats['kursus_aktif'],
                'kursus_pending' => $total_stats['kursus_pending'],
                'total_enrollment' => $total_stats['total_enrollment'],
                'avg_rating' => number_format($total_stats['avg_rating'], 1),
                'pendapatan_bulan_ini' => $this->format_rupiah($monthly_stats['pendapatan_bulan_ini']),
                'transaksi_bulan_ini' => $monthly_stats['transaksi_bulan_ini'],
                'timestamp' => date('H:i:s')
            )
        );
        
        echo json_encode($response);
    }
}