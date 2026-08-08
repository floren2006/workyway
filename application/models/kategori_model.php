<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_kategori()
    {
        $this->db->select('kk.*, COUNT(k.kursus_id) as total_kursus');
        $this->db->from('kategori_kursus kk');
        $this->db->join('kursus k', 'kk.kategori_id = k.kategori_id', 'left');
        $this->db->where('k.status_kursus', 'active');
        $this->db->group_by('kk.kategori_id');
        $this->db->order_by('kk.nama_kategori', 'ASC');
        
        return $this->db->get()->result();
    }
}