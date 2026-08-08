<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tugas_model extends CI_Model
{
    protected $table = 'tugas';
    
    // Method ini mungkin tidak perlu jika tidak ada kolom status
    public function getTugasWithDetails($status = null)
    {
        $this->db->select('tugas.*, 
                          materi_kursus.judul_materi, 
                          kursus.judul_kursus, 
                          kursus.kursus_id');
        $this->db->from('tugas');
        $this->db->join('materi_kursus', 'materi_kursus.materi_id = tugas.materi_id');
        $this->db->join('kursus', 'kursus.kursus_id = materi_kursus.kursus_id');
        
        // HAPUS kondisi status jika kolom tidak ada
        // if ($status && $status != 'semua') {
        //     $this->db->where('tugas.status', $status);
        // }
        
        $this->db->order_by('tugas.created_at', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function getDetail($tugas_id)
    {
        $this->db->select('tugas.*, 
                          materi_kursus.judul_materi, 
                          materi_kursus.deskripsi as deskripsi_materi,
                          kursus.judul_kursus, 
                          kursus.kursus_id,
                          kursus.lpk_id');
        $this->db->from('tugas');
        $this->db->join('materi_kursus', 'materi_kursus.materi_id = tugas.materi_id');
        $this->db->join('kursus', 'kursus.kursus_id = materi_kursus.kursus_id');
        $this->db->where('tugas.tugas_id', $tugas_id);
        
        return $this->db->get()->row_array();
    }

    public function getByLpk($lpk_id)
    {
        $this->db->select('tugas.*, 
                          materi_kursus.judul_materi, 
                          kursus.judul_kursus, 
                          kursus.kursus_id');
        $this->db->from('tugas');
        $this->db->join('materi_kursus', 'materi_kursus.materi_id = tugas.materi_id');
        $this->db->join('kursus', 'kursus.kursus_id = materi_kursus.kursus_id');
        $this->db->where('kursus.lpk_id', $lpk_id);
        
        // HAPUS kondisi status jika kolom tidak ada
        // if ($status && $status != 'semua') {
        //     $this->db->where('tugas.status', $status);
        // }
        
        $this->db->order_by('tugas.created_at', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function insert($data)
    {
        return $this->db->insert('tugas', $data);
    }

    // Tambahkan method CRUD lainnya jika perlu
    public function getById($id)
    {
        return $this->db->get_where($this->table, ['tugas_id' => $id])->row_array();
    }
    
    public function update($id, $data)
    {
        return $this->db->where('tugas_id', $id)->update($this->table, $data);
    }
    
    public function delete($id)
    {
        return $this->db->where('tugas_id', $id)->delete($this->table);
    }
}