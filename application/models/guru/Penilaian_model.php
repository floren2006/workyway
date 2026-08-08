<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get guru_id by user_id
     */
    public function get_guru_id_by_user_id($user_id) {
        $this->db->select('guru_id');
        $this->db->from('guru_freelance');
        $this->db->where('user_id', $user_id);
        $result = $this->db->get()->row();
        return $result ? $result->guru_id : null;
    }

    /**
     * Get courses taught by guru
     */
    public function get_kursus_by_guru($guru_id) {
        $this->db->select('kursus_id, judul_kursus, guru_id');
        $this->db->from('kursus');
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'active');
        return $this->db->get()->result_array();
    }

    /**
     * Count tasks that need grading
     */
    public function count_perlu_dinilai($guru_id) {
        // Get courses taught by this guru
        $kursus_list = $this->get_kursus_by_guru($guru_id);
        if (empty($kursus_list)) return 0;
        
        $kursus_ids = array_column($kursus_list, 'kursus_id');
        
        // Count submissions that need grading
        $this->db->select('COUNT(DISTINCT pt.pengumpulan_id) as total');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->where_in('mk.kursus_id', $kursus_ids);
        $this->db->where('pt.status', 'dikumpulkan');
        $this->db->where('pt.nilai IS NULL');
        $result = $this->db->get()->row_array();
        
        return $result['total'] ?? 0;
    }

    /**
     * Count tasks already graded
     */
    public function count_sudah_dinilai($guru_id) {
        // Get courses taught by this guru
        $kursus_list = $this->get_kursus_by_guru($guru_id);
        if (empty($kursus_list)) return 0;
        
        $kursus_ids = array_column($kursus_list, 'kursus_id');
        
        // Count graded submissions
        $this->db->select('COUNT(DISTINCT pt.pengumpulan_id) as total');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->where_in('mk.kursus_id', $kursus_ids);
        $this->db->where('pt.status', 'dinilai');
        $this->db->where('pt.nilai IS NOT NULL');
        $result = $this->db->get()->row_array();
        
        return $result['total'] ?? 0;
    }

    /**
     * Calculate average grade
     */
    public function rata_rata_nilai($guru_id) {
        // Get courses taught by this guru
        $kursus_list = $this->get_kursus_by_guru($guru_id);
        if (empty($kursus_list)) return 0;
        
        $kursus_ids = array_column($kursus_list, 'kursus_id');
        
        // Calculate average grade
        $this->db->select('AVG(pt.nilai) as rata_rata');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->where_in('mk.kursus_id', $kursus_ids);
        $this->db->where('pt.status', 'dinilai');
        $this->db->where('pt.nilai IS NOT NULL');
        $result = $this->db->get()->row_array();
        
        return round($result['rata_rata'] ?? 0, 1);
    }

    /**
     * Get all submissions for grading
     */
    public function get_pengumpulan_tugas($guru_id) {
        // Get courses taught by this guru
        $kursus_list = $this->get_kursus_by_guru($guru_id);
        if (empty($kursus_list)) return array();
        
        $kursus_ids = array_column($kursus_list, 'kursus_id');
        
        // Build the query
        $this->db->select('
            pt.pengumpulan_id,
            pt.siswa_id,
            pt.tanggal_kumpul,
            pt.status,
            pt.nilai,
            pt.feedback,
            pt.file_tugas,
            pt.link_pengumpulan,
            u.nama as nama_siswa,
            u.foto_profil as foto_siswa,
            k.judul_kursus,
            mk.judul_materi,
            t.judul_tugas,
            t.deadline,
            t.max_score
        ');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'pt.siswa_id = s.siswa_id', 'left');
        $this->db->join('users u', 's.user_id = u.user_id', 'left');
        $this->db->where_in('k.kursus_id', $kursus_ids);
        $this->db->order_by('pt.tanggal_kumpul', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get submission details by ID
     */
    public function get_pengumpulan_by_id($pengumpulan_id, $guru_id) {
        // Verify that this submission belongs to a course taught by this guru
        $this->db->select('
            pt.*,
            u.nama as nama_siswa,
            u.email as email_siswa,
            u.foto_profil as foto_siswa,
            k.kursus_id,
            k.judul_kursus,
            k.guru_id,
            mk.materi_id,
            mk.judul_materi,
            t.*
        ');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'pt.siswa_id = s.siswa_id', 'left');
        $this->db->join('users u', 's.user_id = u.user_id', 'left');
        $this->db->where('pt.pengumpulan_id', $pengumpulan_id);
        $this->db->where('k.guru_id', $guru_id);
        
        $result = $this->db->get()->row_array();
        return $result;
    }

    /**
     * Update grade
     */
    public function update_nilai($pengumpulan_id, $data, $guru_id) {
        // Verify permission first
        $pengumpulan = $this->get_pengumpulan_by_id($pengumpulan_id, $guru_id);
        
        if ($pengumpulan) {
            // Update data
            $this->db->where('pengumpulan_id', $pengumpulan_id);
            return $this->db->update('pengumpulan_tugas', $data);
        }
        
        return false;
    }
    
    /**
     * Get task statistics for dashboard
     */
    public function get_tugas_statistics($guru_id) {
        // Get courses taught by this guru
        $kursus_list = $this->get_kursus_by_guru($guru_id);
        if (empty($kursus_list)) {
            return array(
                'perlu_dinilai' => 0,
                'sudah_dinilai' => 0,
                'rata_rata' => 0
            );
        }
        
        $kursus_ids = array_column($kursus_list, 'kursus_id');
        
        // Get statistics
        $this->db->select('
            COUNT(CASE WHEN pt.status = "dikumpulkan" AND pt.nilai IS NULL THEN 1 END) as perlu_dinilai,
            COUNT(CASE WHEN pt.status = "dinilai" AND pt.nilai IS NOT NULL THEN 1 END) as sudah_dinilai,
            AVG(CASE WHEN pt.status = "dinilai" THEN pt.nilai ELSE NULL END) as rata_rata
        ');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->where_in('mk.kursus_id', $kursus_ids);
        $result = $this->db->get()->row_array();
        
        return array(
            'perlu_dinilai' => $result['perlu_dinilai'] ?? 0,
            'sudah_dinilai' => $result['sudah_dinilai'] ?? 0,
            'rata_rata' => round($result['rata_rata'] ?? 0, 1)
        );
    }
    
    /**
     * Get tasks by course for filter
     */
    public function get_tugas_by_kursus_guru($kursus_id, $guru_id) {
        $this->db->select('
            pt.pengumpulan_id,
            pt.siswa_id,
            pt.tanggal_kumpul,
            pt.status,
            pt.nilai,
            pt.feedback,
            pt.file_tugas,
            pt.link_pengumpulan,
            u.nama as nama_siswa,
            u.foto_profil as foto_siswa,
            k.judul_kursus,
            mk.judul_materi,
            t.judul_tugas,
            t.deadline,
            t.max_score
        ');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'pt.siswa_id = s.siswa_id', 'left');
        $this->db->join('users u', 's.user_id = u.user_id', 'left');
        $this->db->where('k.kursus_id', $kursus_id);
        $this->db->where('k.guru_id', $guru_id);
        $this->db->order_by('pt.tanggal_kumpul', 'DESC');
        
        return $this->db->get()->result_array();
    }
}