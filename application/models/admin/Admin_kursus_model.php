<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_kursus_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all courses with related data
    public function get_all_courses() {
        $this->db->select('k.*, 
            kk.nama_kategori, 
            l.nama_lembaga, 
            gf.keahlian,
            u.nama as nama_guru,
            COUNT(e.enrollment_id) as total_peserta');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->join('lpk l', 'k.lpk_id = l.lpk_id', 'left');
        $this->db->join('guru_freelance gf', 'k.guru_id = gf.guru_id', 'left');
        $this->db->join('users u', 'gf.user_id = u.user_id', 'left');
        $this->db->join('enrollment e', 'k.kursus_id = e.kursus_id', 'left');
        $this->db->group_by('k.kursus_id');
        $this->db->order_by('k.tanggal_dibuat', 'DESC');
        return $this->db->get()->result_array();
    }

    // Get single course by ID
    public function get_course_by_id($id) {
        $this->db->select('k.*, kk.nama_kategori, l.nama_lembaga, u.nama as nama_guru');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->join('lpk l', 'k.lpk_id = l.lpk_id', 'left');
        $this->db->join('guru_freelance gf', 'k.guru_id = gf.guru_id', 'left');
        $this->db->join('users u', 'gf.user_id = u.user_id', 'left');
        $this->db->where('k.kursus_id', $id);
        return $this->db->get()->row_array();
    }

    // Add new course
    public function add_course($data) {
        return $this->db->insert('kursus', $data);
    }

    // Update course
    public function update_course($id, $data) {
        $this->db->where('kursus_id', $id);
        return $this->db->update('kursus', $data);
    }

    // Delete course
    public function delete_course($id) {
        $this->db->where('kursus_id', $id);
        return $this->db->delete('kursus');
    }

    // Get course statistics
    public function get_course_stats() {
        $stats = array();
        
        // Total courses
        $stats['total_courses'] = $this->db->count_all('kursus');
        
        // Active courses
        $this->db->where('status_kursus', 'active');
        $stats['active_courses'] = $this->db->count_all_results('kursus');
        
        // Pending courses
        $this->db->where('status_kursus', 'pending');
        $stats['pending_courses'] = $this->db->count_all_results('kursus');
        
        // Inactive courses
        $this->db->where('status_kursus', 'inactive');
        $stats['inactive_courses'] = $this->db->count_all_results('kursus');
        
        // Total enrollment
        $this->db->select('COUNT(*) as total');
        $stats['total_enrollment'] = $this->db->get('enrollment')->row()->total;
        
        return $stats;
    }

    // Search courses
    public function search_courses($keyword) {
        $this->db->select('k.*, kk.nama_kategori, l.nama_lembaga, u.nama as nama_guru');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->join('lpk l', 'k.lpk_id = l.lpk_id', 'left');
        $this->db->join('guru_freelance gf', 'k.guru_id = gf.guru_id', 'left');
        $this->db->join('users u', 'gf.user_id = u.user_id', 'left');
        
        // Search across multiple fields
        $this->db->group_start();
        $this->db->like('k.judul_kursus', $keyword);
        $this->db->or_like('k.deskripsi', $keyword);
        $this->db->or_like('kk.nama_kategori', $keyword);
        $this->db->or_like('l.nama_lembaga', $keyword);
        $this->db->or_like('u.nama', $keyword);
        $this->db->or_like('k.status_kursus', $keyword);
        $this->db->group_end();
        
        $this->db->order_by('k.kursus_id', 'DESC');
        return $this->db->get()->result_array();
    }
    
    // =========================================
    // CRUD KATEGORI METHODS
    // =========================================
    
    // Get all categories
    public function get_all_categories() {
        $this->db->select('kk.*, COUNT(k.kursus_id) as jumlah_kursus');
        $this->db->from('kategori_kursus kk');
        $this->db->join('kursus k', 'kk.kategori_id = k.kategori_id', 'left');
        $this->db->group_by('kk.kategori_id');
        $this->db->order_by('kk.nama_kategori', 'ASC');
        return $this->db->get()->result_array();
    }
    
    // Get single category by ID
    public function get_category_by_id($id) {
        $this->db->where('kategori_id', $id);
        return $this->db->get('kategori_kursus')->row_array();
    }
    
    // Add new category
    public function add_category($data) {
        return $this->db->insert('kategori_kursus', $data);
    }
    
    // Update category
    public function update_category($id, $data) {
        $this->db->where('kategori_id', $id);
        return $this->db->update('kategori_kursus', $data);
    }
    
    // Delete category
    public function delete_category($id) {
        $this->db->where('kategori_id', $id);
        return $this->db->delete('kategori_kursus');
    }
    
    // Check if category is used in courses
    public function check_category_usage($kategori_id) {
        $this->db->where('kategori_id', $kategori_id);
        return $this->db->count_all_results('kursus');
    }
    
    // Get categories for dropdown
    public function get_categories_dropdown() {
        $this->db->select('kategori_id, nama_kategori');
        $this->db->order_by('nama_kategori', 'ASC');
        $query = $this->db->get('kategori_kursus');
        $result = $query->result_array();
        
        $dropdown = array('' => 'Pilih Kategori');
        foreach ($result as $row) {
            $dropdown[$row['kategori_id']] = $row['nama_kategori'];
        }
        
        return $dropdown;
    }
    
    // =========================================
    // DATA UNTUK DROPDOWN
    // =========================================
    
    // Get LPK for dropdown
    public function get_lpk_dropdown() {
        $this->db->select('lpk_id, nama_lembaga');
        $this->db->where('status_verifikasi', 'approved');
        $this->db->order_by('nama_lembaga', 'ASC');
        $query = $this->db->get('lpk');
        $result = $query->result_array();
        
        $dropdown = array('' => 'Pilih LPK');
        foreach ($result as $row) {
            $dropdown[$row['lpk_id']] = $row['nama_lembaga'];
        }
        
        return $dropdown;
    }
    
    // Get Guru freelance for dropdown
    public function get_guru_dropdown() {
        $this->db->select('gf.guru_id, u.nama');
        $this->db->from('guru_freelance gf');
        $this->db->join('users u', 'gf.user_id = u.user_id');
        $this->db->where('gf.status_verifikasi', 'approved');
        $this->db->order_by('u.nama', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        
        $dropdown = array('' => 'Pilih Guru');
        foreach ($result as $row) {
            $dropdown[$row['guru_id']] = $row['nama'];
        }
        
        return $dropdown;
    }
    
    // Get status options
    public function get_status_options() {
        return array(
            'pending' => 'Pending',
            'active' => 'Active',
            'inactive' => 'Inactive'
        );
    }
}
?>