<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_notifikasi_by_user($user_id, $limit = 10) {
        $this->db->where('penerima_id', $user_id);
        $this->db->order_by('tanggal', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('notifikasi')->result();
    }
}