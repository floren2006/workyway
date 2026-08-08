<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Ambil materi berdasarkan kursus
     */
    public function get_materi_by_kursus($kursus_id) {
        $this->db->select('mk.*, 
                          COUNT(DISTINCT ft.file_id) as jumlah_file,
                          COUNT(DISTINCT t.tugas_id) as jumlah_tugas');
        $this->db->from('materi_kursus mk');
        $this->db->join('file_tambahan ft', 'mk.materi_id = ft.materi_id', 'left');
        $this->db->join('tugas t', 'mk.materi_id = t.materi_id', 'left');
        $this->db->where('mk.kursus_id', $kursus_id);
        $this->db->group_by('mk.materi_id');
        $this->db->order_by('mk.tanggal_upload', 'ASC');
        return $this->db->get()->result_array();
    }
}