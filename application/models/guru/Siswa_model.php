<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Ambil daftar siswa yang mengikuti kursus guru
     */
    public function get_daftar_siswa($guru_id) {
        // Query untuk mengambil siswa yang terdaftar di kursus yang dihandle oleh guru
        $this->db->distinct();
        $this->db->select('
            s.siswa_id, 
            u.nama, 
            u.email, 
            u.telepon, 
            u.foto_profil, 
            s.tanggal_lahir, 
            s.pendidikan_terakhir,
            s.jurusan_id,
            GROUP_CONCAT(DISTINCT k.judul_kursus ORDER BY k.judul_kursus SEPARATOR ", ") as kursus_diikuti,
            GROUP_CONCAT(DISTINCT k.kursus_id) as kursus_ids,
            MAX(e.tanggal_daftar) as tanggal_daftar_terakhir,
            COUNT(DISTINCT e.kursus_id) as jumlah_kursus,
            COALESCE(AVG(e.rating), 0) as rating_rata2
        ');
        $this->db->from('siswa s');
        $this->db->join('users u', 's.user_id = u.user_id');
        $this->db->join('enrollment e', 's.siswa_id = e.siswa_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->group_by('s.siswa_id, u.nama, u.email, u.telepon, u.foto_profil, s.tanggal_lahir, s.pendidikan_terakhir, s.jurusan_id');
        $this->db->order_by('u.nama', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Ambil detail siswa lengkap
     */
    public function get_detail_siswa($siswa_id, $guru_id) {
        $this->db->select('
            s.*,
            u.nama, 
            u.email, 
            u.telepon, 
            u.alamat,
            u.foto_profil,
            j.nama_jurusan,
            GROUP_CONCAT(DISTINCT k.judul_kursus ORDER BY k.judul_kursus SEPARATOR ", ") as kursus_diikuti,
            COUNT(DISTINCT e.kursus_id) as total_kursus,
            COALESCE(AVG(e.rating), 0) as rating_rata2,
            COALESCE(AVG(e.nilai), 0) as nilai_rata2
        ');
        $this->db->from('siswa s');
        $this->db->join('users u', 's.user_id = u.user_id');
        $this->db->join('jurusan j', 's.jurusan_id = j.jurusan_id', 'left');
        $this->db->join('enrollment e', 's.siswa_id = e.siswa_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('s.siswa_id', $siswa_id);
        $this->db->where('k.guru_id', $guru_id);
        $this->db->group_by('s.siswa_id, u.nama, u.email, u.telepon, u.alamat, u.foto_profil, j.nama_jurusan');
        
        return $this->db->get()->row_array();
    }

    /**
     * Ambil jumlah kursus aktif yang dihandle guru
     */
    public function get_jumlah_kursus_aktif($guru_id) {
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'active');
        return $this->db->count_all_results('kursus');
    }

    /**
     * Ambil statistik lengkap siswa per guru
     */
    public function get_statistik_siswa($guru_id) {
        $statistik = array();
        
        // Total siswa unik
        $this->db->select('COUNT(DISTINCT s.siswa_id) as total_siswa');
        $this->db->from('siswa s');
        $this->db->join('enrollment e', 's.siswa_id = e.siswa_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        $statistik['total_siswa'] = $result['total_siswa'] ?? 0;
        
        // Total enrollment
        $this->db->select('COUNT(e.enrollment_id) as total_enrollment');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $query = $this->db->get()->row_array();
        $statistik['total_enrollment'] = $query['total_enrollment'] ?? 0;
        
        // Kursus dengan siswa terbanyak
        $this->db->select('k.judul_kursus, COUNT(DISTINCT e.siswa_id) as jumlah_siswa');
        $this->db->from('kursus k');
        $this->db->join('enrollment e', 'k.kursus_id = e.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->group_by('k.kursus_id');
        $this->db->order_by('jumlah_siswa', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get()->row_array();
        $statistik['kursus_populer'] = $query['judul_kursus'] ?? '-';
        $statistik['jumlah_siswa_populer'] = $query['jumlah_siswa'] ?? 0;
        
        // Jumlah tugas yang dikumpulkan
        $statistik['total_tugas'] = $this->count_tugas_siswa($guru_id);
        
        // Jumlah forum diskusi
        $statistik['total_forum'] = $this->count_forum_siswa($guru_id);
        
        return $statistik;
    }

    /**
     * Hitung total tugas yang dikumpulkan siswa
     */
    private function count_tugas_siswa($guru_id) {
        $this->db->select('COUNT(DISTINCT pt.pengumpulan_id) as total');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->join('kursus k', 'm.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('pt.status', 'dikumpulkan');
        $result = $this->db->get()->row_array();
        
        return $result['total'] ?? 0;
    }

    /**
     * Hitung total forum diskusi dari siswa
     */
    private function count_forum_siswa($guru_id) {
        $this->db->select('COUNT(DISTINCT f.forum_id) as total');
        $this->db->from('forum f');
        $this->db->join('kursus k', 'f.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        
        return $result['total'] ?? 0;
    }
}