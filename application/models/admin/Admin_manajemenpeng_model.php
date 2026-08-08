<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_manajemenpeng_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get total statistics - exclude admin
    public function get_total_users() {
        $this->db->where('role !=', 'admin');
        $this->db->where('user_id !=', 1);
        return $this->db->count_all_results('users');
    }
    
    public function get_total_siswa() {
        $this->db->where('role', 'siswa');
        $this->db->where('user_id !=', 1);
        return $this->db->count_all_results('users');
    }
    
    public function get_total_lpk() {
        $this->db->where('role', 'lpk');
        $this->db->where('user_id !=', 1);
        return $this->db->count_all_results('users');
    }
    
    public function get_total_guru() {
        $this->db->where('role', 'guru');
        $this->db->where('user_id !=', 1);
        return $this->db->count_all_results('users');
    }
    
    // Get ALL users without pagination
    public function get_all_users($search = null, $filter = null) {
        $this->db->select('u.*, 
                          g.status_verifikasi as guru_status,
                          l.status_verifikasi as lpk_status,
                          l.nama_lembaga');
        $this->db->from('users u');
        $this->db->join('siswa s', 'u.user_id = s.user_id AND u.role = "siswa"', 'left');
        $this->db->join('guru_freelance g', 'u.user_id = g.user_id AND u.role = "guru"', 'left');
        $this->db->join('lpk l', 'u.user_id = l.user_id AND u.role = "lpk"', 'left');
        $this->db->where('u.role !=', 'admin');
        $this->db->where('u.user_id !=', 1); // Exclude admin ID 1
        
        // Apply search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('u.nama', $search);
            $this->db->or_like('u.email', $search);
            $this->db->or_like('l.nama_lembaga', $search);
            $this->db->group_end();
        }
        
        // Apply role filter
        if (!empty($filter)) {
            switch($filter) {
                case 'siswa':
                    $this->db->where('u.role', 'siswa');
                    break;
                case 'lpk':
                    $this->db->where('u.role', 'lpk');
                    break;
                case 'guru':
                    $this->db->where('u.role', 'guru');
                    break;
                case 'aktif':
                    $this->db->group_start();
                    $this->db->where('u.status_aktif', 1);
                    $this->db->or_where('g.status_verifikasi', 'approved');
                    $this->db->or_where('l.status_verifikasi', 'approved');
                    $this->db->group_end();
                    break;
                case 'pending':
                    $this->db->group_start();
                    $this->db->where('g.status_verifikasi', 'pending');
                    $this->db->or_where('l.status_verifikasi', 'pending');
                    $this->db->group_end();
                    break;
                case 'nonaktif':
                    $this->db->group_start();
                    $this->db->where('u.status_aktif', 0);
                    $this->db->or_where('g.status_verifikasi', 'rejected');
                    $this->db->or_where('l.status_verifikasi', 'rejected');
                    $this->db->group_end();
                    break;
            }
        }
        
        $this->db->order_by('u.tanggal_daftar', 'DESC');
        
        $query = $this->db->get();
        $users = $query->result_array();
        
        // Get course count for each user
        foreach($users as &$user) {
            $user['jumlah_kursus'] = $this->get_course_count($user['user_id'], $user['role']);
        }
        
        return $users;
    }
    
    // Get course count based on user role
    public function get_course_count($user_id, $role) {
        $count = 0;
        
        switch($role) {
            case 'siswa':
                $this->db->select('COUNT(*) as total');
                $this->db->from('enrollment e');
                $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
                $this->db->where('s.user_id', $user_id);
                $query = $this->db->get();
                $result = $query->row();
                $count = $result ? $result->total : 0;
                break;
                
            case 'guru':
                $this->db->select('COUNT(*) as total');
                $this->db->from('kursus k');
                $this->db->join('guru_freelance g', 'k.guru_id = g.guru_id');
                $this->db->where('g.user_id', $user_id);
                $query = $this->db->get();
                $result = $query->row();
                $count = $result ? $result->total : 0;
                break;
                
            case 'lpk':
                $this->db->select('COUNT(*) as total');
                $this->db->from('kursus k');
                $this->db->join('lpk l', 'k.lpk_id = l.lpk_id');
                $this->db->where('l.user_id', $user_id);
                $query = $this->db->get();
                $result = $query->row();
                $count = $result ? $result->total : 0;
                break;
        }
        
        return $count;
    }
    
    // Update user status
    public function update_user_status($user_id, $status) {
        $data = array('status_aktif' => ($status == 'active' ? 1 : 0));
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }
    
    // Delete user
    public function delete_user($user_id) {
        // Cek role user
        $this->db->select('role');
        $this->db->where('user_id', $user_id);
        $user = $this->db->get('users')->row();
        
        if (!$user) return false;
        
        // Jangan izinkan hapus user ID 1 (admin)
        if ($user_id == 1) return false;
        
        // Mulai transaction
        $this->db->trans_start();
        
        // Hapus dari tabel role sesuai
        switch($user->role) {
            case 'siswa':
                $this->db->where('user_id', $user_id);
                $this->db->delete('siswa');
                break;
            case 'guru':
                $this->db->where('user_id', $user_id);
                $this->db->delete('guru_freelance');
                break;
            case 'lpk':
                $this->db->where('user_id', $user_id);
                $this->db->delete('lpk');
                break;
        }
        
        // Hapus dari users
        $this->db->where('user_id', $user_id);
        $delete_result = $this->db->delete('users');
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() && $delete_result;
    }
}