<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {
public function getByLpk($lpk_id, $kursus_id = null, $instruktur_id = null)
{
    $this->db->select('
        u.nama AS nama_siswa,
        k.judul_kursus,
        i.nama AS nama_instruktur,
        e.nilai,
        e.sertifikat
    ');
    $this->db->from('enrollment e');
    $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
    $this->db->join('instruktur i', 'i.instruktur_id = k.instruktur_id');
    $this->db->join('siswa s', 's.siswa_id = e.siswa_id');
    $this->db->join('users u', 'u.user_id = s.user_id');
    $this->db->where('k.lpk_id', $lpk_id);

    // FILTER KURSUS
    if (!empty($kursus_id)) {
        $this->db->where('k.kursus_id', $kursus_id);
    }

    // FILTER INSTRUKTUR
    if (!empty($instruktur_id)) {
        $this->db->where('i.instruktur_id', $instruktur_id);
    }

    return $this->db->get()->result_array();
}
}