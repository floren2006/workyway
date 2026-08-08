<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_manajemen_kursus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Admin_kursus_model');
        $this->load->helper('url');
        $this->load->library('session');
        
        // BYPASS LOGIN - GUNAKAN USER ID 1
        $this->session->set_userdata(array(
            'admin_logged_in' => TRUE,
            'user_id' => 1,
            'nama' => 'Administrator',
            'role' => 'admin'
        ));
        
        // Set active menu
        $this->data['active_menu'] = 'management_kursus';
    }

    // Main page - DAFTAR KURSUS
    public function index() {
        $data = array();
        $data['active_menu'] = 'management_kursus';
        
        // Get search keyword
        $keyword = $this->input->get('search');
        
        if (!empty($keyword)) {
            $data['courses'] = $this->Admin_kursus_model->search_courses($keyword);
            $data['search_keyword'] = $keyword;
        } else {
            $data['courses'] = $this->Admin_kursus_model->get_all_courses();
            $data['search_keyword'] = '';
        }
        
        // Get statistics
        $data['stats'] = $this->Admin_kursus_model->get_course_stats();
        
        // Get all categories for dropdown
        $data['categories'] = $this->Admin_kursus_model->get_all_categories();
        
        // Load view
        $this->load->view('admin/admin_kursus_view', $data);
    }

    // Search function (alternatif dengan POST method)
    public function search() {
        $keyword = $this->input->post('search');
        
        if (!empty($keyword)) {
            $data['courses'] = $this->Admin_kursus_model->search_courses($keyword);
            $data['search_keyword'] = $keyword;
        } else {
            $data['courses'] = $this->Admin_kursus_model->get_all_courses();
            $data['search_keyword'] = '';
        }
        
        // Get statistics
        $data['stats'] = $this->Admin_kursus_model->get_course_stats();
        
        // Get all categories for dropdown
        $data['categories'] = $this->Admin_kursus_model->get_all_categories();
        
        // Load view
        $this->load->view('admin/admin_kursus_view', $data);
    }

    // Approve course (ubah status dari pending ke active)
    public function approve($id) {
        $data = array('status_kursus' => 'active');
        
        if ($this->Admin_kursus_model->update_course($id, $data)) {
            $this->session->set_flashdata('success', 'Kursus berhasil disetujui dan diaktifkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyetujui kursus!');
        }
        
        redirect('admin/manajemen_kursus');
    }

    // Reject course (ubah status dari pending ke inactive)
    public function reject($id) {
        $data = array('status_kursus' => 'inactive');
        
        if ($this->Admin_kursus_model->update_course($id, $data)) {
            $this->session->set_flashdata('success', 'Kursus berhasil ditolak!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak kursus!');
        }
        
        redirect('admin/manajemen_kursus');
    }

    // Delete course
    public function hapus($id) {
        if ($this->Admin_kursus_model->delete_course($id)) {
            $this->session->set_flashdata('success', 'Kursus berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kursus!');
        }
        
        redirect('admin/manajemen_kursus');
    }
    
    // CRUD KATEGORI
    // =========================================
    
    // Tambah Kategori
    public function tambah_kategori() {
        if ($this->input->post()) {
            $data = array(
                'nama_kategori' => $this->input->post('nama_kategori'),
                'deskripsi' => $this->input->post('deskripsi')
            );
            
            if ($this->Admin_kursus_model->add_category($data)) {
                $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kategori!');
            }
            
            redirect('admin/manajemen_kursus');
        }
    }
    
    // Edit Kategori - Form
    public function edit_kategori($id) {
        $data['category'] = $this->Admin_kursus_model->get_category_by_id($id);
        $data['categories'] = $this->Admin_kursus_model->get_all_categories();
        $data['courses'] = $this->Admin_kursus_model->get_all_courses();
        $data['stats'] = $this->Admin_kursus_model->get_course_stats();
        $data['active_menu'] = 'management_kursus';
        
        $this->load->view('admin/admin_kursus_view', $data);
    }
    
    // Update Kategori
    public function update_kategori($id) {
        if ($this->input->post()) {
            $data = array(
                'nama_kategori' => $this->input->post('nama_kategori'),
                'deskripsi' => $this->input->post('deskripsi')
            );
            
            if ($this->Admin_kursus_model->update_category($id, $data)) {
                $this->session->set_flashdata('success', 'Kategori berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui kategori!');
            }
            
            redirect('admin/manajemen_kursus');
        }
    }
    
    // Hapus Kategori
    public function hapus_kategori($id) {
        // Cek apakah kategori digunakan oleh kursus
        $used_in_courses = $this->Admin_kursus_model->check_category_usage($id);
        
        if ($used_in_courses > 0) {
            $this->session->set_flashdata('error', 'Kategori tidak dapat dihapus karena digunakan oleh ' . $used_in_courses . ' kursus!');
        } else {
            if ($this->Admin_kursus_model->delete_category($id)) {
                $this->session->set_flashdata('success', 'Kategori berhasil dihapus!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus kategori!');
            }
        }
        
        redirect('admin/manajemen_kursus');
    }
}
?>
