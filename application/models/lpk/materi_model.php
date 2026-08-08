<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi_model extends CI_Model {

    protected $table = 'materi_kursus';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_kursus($kursus_id)
    {
        return $this->db
            ->where('kursus_id', $kursus_id)
            ->get($this->table)
            ->result_array();
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['materi_id' => $id]);
    }
    public function getByLpk($lpk_id)
{
    $this->db->select('materi_kursus.materi_id, materi_kursus.judul_materi, kursus.judul_kursus');
    $this->db->from('materi_kursus');
    $this->db->join('kursus', 'kursus.kursus_id = materi_kursus.kursus_id');
    $this->db->where('kursus.lpk_id', $lpk_id);
    $this->db->order_by('materi_kursus.tanggal_upload', 'DESC');
    
    $query = $this->db->get();
    
    // Debug untuk melihat query yang dihasilkan
    // echo $this->db->last_query(); 
    // die();
    
    return $query->result_array();
}
public function getByKursus($kursus_id)
{
    $this->db->select('materi_id, judul_materi');
    $this->db->from('materi_kursus');
    $this->db->where('kursus_id', $kursus_id);
    $this->db->order_by('tanggal_upload', 'DESC');
    
    return $this->db->get()->result_array();
}
}

