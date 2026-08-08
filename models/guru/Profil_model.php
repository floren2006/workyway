<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_complete_guru_data($guru_id) {
        $this->db->select('gf.*, u.nama, u.email, u.telepon, u.alamat, u.foto_profil, 
                          u.tanggal_daftar, u.status_aktif, u.role');
        $this->db->from('guru_freelance gf');
        $this->db->join('users u', 'gf.user_id = u.user_id');
        $this->db->where('gf.guru_id', $guru_id);
        $query = $this->db->get();
        
        return $query->row_array();
    }

    public function update_guru_profile($guru_id, $data_guru, $data_user) {
        $this->db->trans_start();
        
        // Update tabel guru_freelance
        $this->db->where('guru_id', $guru_id);
        $this->db->update('guru_freelance', $data_guru);
        
        // Update tabel users jika ada data
        if (!empty($data_user)) {
            // Dapatkan user_id dari guru_id
            $user_id = $this->get_user_id_by_guru_id($guru_id);
            if ($user_id) {
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data_user);
            }
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function get_user_id_by_guru_id($guru_id) {
        $this->db->select('user_id');
        $this->db->from('guru_freelance');
        $this->db->where('guru_id', $guru_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->user_id;
        }
        
        return null;
    }
}