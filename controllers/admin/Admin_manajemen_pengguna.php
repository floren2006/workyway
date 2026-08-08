<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_manajemen_pengguna extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Admin_manajemenpeng_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
        
        // AUTO LOGIN - User ID 1 sebagai admin
        if (!$this->session->userdata('admin_logged_in')) {
            $this->auto_login();
        }
    }
    
    private function auto_login() {
        // Cari user dengan ID 1
        $this->db->where('user_id', 1);
        $admin = $this->db->get('users')->row_array();
        
        if (!$admin) {
            // Jika user ID 1 tidak ada, buat admin default
            $admin_data = array(
                'user_id' => 1,
                'nama' => 'Administrator',
                'email' => 'admin@system.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'tanggal_daftar' => date('Y-m-d H:i:s'),
                'status_aktif' => 1
            );
            $this->db->insert('users', $admin_data);
            $admin = $admin_data;
        }
        
        // Set session
        $this->session->set_userdata(array(
            'admin_logged_in' => TRUE,
            'admin_id' => 1,
            'admin_name' => $admin['nama'],
            'admin_email' => $admin['email']
        ));
        
        return true;
    }
    
    public function index() {
        $data['page_title'] = 'Manajemen Pengguna';
        
        // Get search and filter parameters
        $search = $this->input->get('search');
        $filter = $this->input->get('filter');
        
        // Get user statistics
        $data['total_users'] = $this->Admin_manajemenpeng_model->get_total_users();
        $data['total_siswa'] = $this->Admin_manajemenpeng_model->get_total_siswa();
        $data['total_lpk'] = $this->Admin_manajemenpeng_model->get_total_lpk();
        $data['total_guru'] = $this->Admin_manajemenpeng_model->get_total_guru();
        
        // Get ALL users tanpa pagination
        $data["users"] = $this->Admin_manajemenpeng_model->get_all_users($search, $filter);
        $data["total_rows"] = count($data["users"]);
        
        // Pass session data to view
        $data['admin_name'] = $this->session->userdata('admin_name');
        $data['search'] = $search;
        $data['filter'] = $filter;
        
        $this->load->view('admin/admin_manajemenpeng_view', $data);
    }
    
    public function logout() {
        $this->session->sess_destroy();
        redirect('admin/manajemen_pengguna');
    }
}