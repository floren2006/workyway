<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Ambil data guru berdasarkan ID
     */
    public function get_guru_by_id($guru_id) {
        $this->db->select('g.*, u.nama, u.email, u.foto_profil');
        $this->db->from('guru_freelance g');
        $this->db->join('users u', 'g.user_id = u.user_id');
        $this->db->where('g.guru_id', $guru_id);
        return $this->db->get()->row_array();
    }

    /**
     * Ambil statistik lengkap untuk dashboard guru
     */
    public function get_statistik($guru_id = 1) {
        $statistik = array();
        
        // 1. Total kursus aktif
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'active');
        $statistik['total_kursus'] = $this->db->count_all_results('kursus');
        
        // 2. Total siswa unik
        $this->db->select('COUNT(DISTINCT e.siswa_id) as total_siswa');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        $statistik['total_siswa'] = $result['total_siswa'] ?? 0;
        
        // 3. Rating rata-rata
        $this->db->select('AVG(e.rating) as avg_rating');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('e.rating IS NOT NULL');
        $result = $this->db->get()->row_array();
        $statistik['avg_rating'] = round($result['avg_rating'] ?? 0, 1);
        
        // 4. Total pendapatan
        $this->db->select('SUM(t.gaji) as total_pendapatan');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('t.status', 'success');
        $result = $this->db->get()->row_array();
        $statistik['total_pendapatan'] = $result['total_pendapatan'] ?? 0;
        
        // 5. Pendapatan bulan ini
        $bulan_ini = date('Y-m');
        $this->db->select('SUM(t.gaji) as pendapatan_bulan');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('t.status', 'success');
        $this->db->like('t.tanggal_transaksi', $bulan_ini);
        $result = $this->db->get()->row_array();
        $statistik['pendapatan_bulan_ini'] = $result['pendapatan_bulan'] ?? 0;
        
        // 6. Total forum diskusi
        $this->db->select('COUNT(f.forum_id) as total_forum');
        $this->db->from('forum f');
        $this->db->join('kursus k', 'f.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        $statistik['total_forum'] = $result['total_forum'] ?? 0;
        
        // 7. Tugas yang perlu dinilai
        $statistik['tugas_perlu_dinilai'] = $this->count_tugas_perlu_dinilai($guru_id);
        
        return $statistik;
    }

    /**
     * Hitung tugas yang perlu dinilai
     */
    private function count_tugas_perlu_dinilai($guru_id) {
        // Query untuk menghitung tugas yang belum dinilai
        $this->db->select('COUNT(DISTINCT pt.pengumpulan_id) as total');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->join('kursus k', 'm.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('pt.nilai IS NULL');
        $result = $this->db->get()->row_array();
        
        return $result['total'] ?? 0;
    }

    /**
     * Ambil kursus aktif guru
     */
    public function get_kursus_aktif($guru_id = 1, $limit = 8) {
        $this->db->select('k.*, kk.nama_kategori, 
                          COUNT(DISTINCT e.siswa_id) as jumlah_siswa,
                          COUNT(DISTINCT f.forum_id) as jumlah_diskusi');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->join('enrollment e', 'k.kursus_id = e.kursus_id', 'left');
        $this->db->join('forum f', 'k.kursus_id = f.kursus_id', 'left');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('k.status_kursus', 'active');
        $this->db->group_by('k.kursus_id');
        $this->db->order_by('k.tanggal_dibuat', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Ambil kursus populer berdasarkan jumlah siswa
     */
    public function get_kursus_populer($guru_id = 1, $limit = 5) {
        $this->db->select('k.kursus_id, k.judul_kursus, k.rating_rata2, k.biaya, 
                          COUNT(DISTINCT e.siswa_id) as jumlah_siswa, 
                          kk.nama_kategori, k.tanggal_dibuat');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->join('enrollment e', 'k.kursus_id = e.kursus_id', 'left');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('k.status_kursus', 'active');
        $this->db->group_by('k.kursus_id');
        $this->db->order_by('jumlah_siswa', 'DESC');
        $this->db->order_by('k.rating_rata2', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Ambil aktivitas terbaru guru
     */
    public function get_aktivitas_terbaru($guru_id = 1, $limit = 10) {
        // Query untuk mendapatkan aktivitas enrollment
        $this->db->select("'enrollment' as tipe, e.tanggal_daftar as waktu, 
                          CONCAT(u.nama, ' mendaftar kursus ', k.judul_kursus) as pesan", FALSE);
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
        $this->db->join('users u', 's.user_id = u.user_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('e.tanggal_daftar >=', date('Y-m-d', strtotime('-7 days')));
        
        $enrollment = $this->db->get_compiled_select();
        
        // Query untuk mendapatkan aktivitas rating
        $this->db->select("'rating' as tipe, e.tanggal_daftar as waktu, 
                          CONCAT(u.nama, ' memberikan rating ', e.rating, ' untuk ', k.judul_kursus) as pesan", FALSE);
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
        $this->db->join('users u', 's.user_id = u.user_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('e.rating IS NOT NULL');
        $this->db->where('e.tanggal_daftar >=', date('Y-m-d', strtotime('-7 days')));
        
        $rating = $this->db->get_compiled_select();
        
        // Gabungkan kedua query dan urutkan berdasarkan waktu
        $query = $this->db->query("($enrollment) UNION ($rating) ORDER BY waktu DESC LIMIT $limit");
        
        return $query->result_array();
    }
}