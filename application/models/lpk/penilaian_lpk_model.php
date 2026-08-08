<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian_lpk_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Hitung tugas yang perlu dinilai oleh LPK
     */
    public function count_perlu_dinilai($lpk_id) {
        $this->db->select('COUNT(DISTINCT pt.pengumpulan_id) as total');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->join('lpk l', 'k.lpk_id = l.lpk_id');
        $this->db->where('l.lpk_id', $lpk_id);
        $this->db->where('pt.status', 'dikumpulkan');
        $this->db->where('pt.tanggal_kumpul IS NOT NULL');

        $result = $this->db->get()->row_array();
        return $result['total'] ?? 0;
    }

    /**
     * Hitung tugas yang sudah dinilai oleh LPK
     */
    public function count_sudah_dinilai($lpk_id) {
        $this->db->select('COUNT(DISTINCT pt.pengumpulan_id) as total');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->join('lpk l', 'k.lpk_id = l.lpk_id');
        $this->db->where('l.lpk_id', $lpk_id);
        $this->db->where('pt.status', 'dinilai');
        $this->db->where('pt.tanggal_penilaian IS NOT NULL');
        $result = $this->db->get()->row_array();
        return $result['total'] ?? 0;
    }

    /**
     * Hitung rata-rata nilai yang diberikan oleh LPK
     */
    public function rata_rata_nilai($lpk_id) {
        $this->db->select('AVG(pt.nilai) as rata_rata');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->join('lpk l', 'k.lpk_id = l.lpk_id');
        $this->db->where('l.lpk_id', $lpk_id);
        $this->db->where('pt.status', 'dinilai');
        $this->db->where('pt.tanggal_penilaian IS NOT NULL');
        $result = $this->db->get()->row_array();
        return round($result['rata_rata'] ?? 0, 1);
    }

    /**
     * Ambil semua pengumpulan tugas untuk dinilai oleh LPK
     */
    public function get_pengumpulan_tugas($lpk_id) {
        $this->db->select('
            pt.pengumpulan_id,
            s.siswa_id,
            pt.tanggal_kumpul,
            pt.status,
            pt.nilai,
            pt.feedback,
            pt.file_tugas,
            pt.link_pengumpulan,
            u.nama as nama_siswa,
            u.foto_profil,
            k.judul_kursus,
            mk.judul_materi,
            t.judul_tugas,
            t.deadline,
            t.max_score,
            u2.nama as nama_instruktur
        ');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        
        // Join untuk instruktur (melalui tabel users)
        $this->db->join('users u2', 'k.guru_id = u2.user_id', 'left');
        
        // Join untuk siswa
        $this->db->join('siswa s', 'pt.siswa_id = s.siswa_id', 'left');
        $this->db->join('users u', 's.user_id = u.user_id', 'left');
        
        $this->db->where('k.lpk_id', $lpk_id);
        $this->db->where('pt.status', 'dikumpulkan');
        $this->db->where('pt.tanggal_kumpul IS NOT NULL');
        $this->db->order_by('pt.tanggal_kumpul', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil detail pengumpulan tugas berdasarkan ID untuk LPK
     */
    public function get_pengumpulan_by_id($pengumpulan_id, $lpk_id) {
        $this->db->select('
            pt.pengumpulan_id,
            pt.tugas_id,
            pt.siswa_id,
            pt.tanggal_kumpul,
            pt.status,
            pt.nilai,
            pt.feedback,
            pt.file_tugas,
            pt.link_pengumpulan,
            pt.catatan as catatan_siswa,
            u.nama as nama_siswa,
            u.email as email_siswa,
            u.foto_profil,
            k.kursus_id,
            k.judul_kursus,
            mk.materi_id,
            mk.judul_materi,
            t.judul_tugas,
            t.deskripsi as deskripsi_tugas,
            t.deadline,
            t.max_score,
            t.tipe_tugas,
            t.file_template,
            u2.nama as nama_instruktur,
            k.guru_id
        ');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        
        // Join untuk instruktur (melalui tabel users)
        $this->db->join('users u2', 'k.guru_id = u2.user_id', 'left');
        
        // Join untuk siswa
        $this->db->join('siswa s', 'pt.siswa_id = s.siswa_id', 'left');
        $this->db->join('users u', 's.user_id = u.user_id', 'left');
        
        $this->db->where('pt.pengumpulan_id', $pengumpulan_id);
        $this->db->where('pt.tanggal_kumpul IS NOT NULL');
        $this->db->where('k.lpk_id', $lpk_id);
        
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function get_tugas_sudah_dinilai($lpk_id)
    {
        $this->db->select('
            pt.pengumpulan_id,
            pt.nilai,
            pt.tanggal_penilaian,
            u.nama as nama_siswa,
            t.judul_tugas,
            k.judul_kursus
        ');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'pt.siswa_id = s.siswa_id');
        $this->db->join('users u', 's.user_id = u.user_id');

        $this->db->where('k.lpk_id', $lpk_id);
        $this->db->where('pt.status', 'dinilai');

        return $this->db->get()->result_array();
    }

    /**
     * Update nilai pengumpulan tugas oleh LPK
     * PERBAIKAN: Sesuaikan dengan cara pemanggilan di controller
     */
    public function update_nilai($pengumpulan_id, $data_update, $lpk_id)
    {
        /* =====================================================
        1. VALIDASI: pastikan pengumpulan valid & milik LPK
        ===================================================== */
        $this->db->select('pt.pengumpulan_id');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->where('pt.pengumpulan_id', $pengumpulan_id);
        $this->db->where('k.lpk_id', $lpk_id);

        // WAJIB: hanya boleh menilai yang benar-benar dikumpulkan
        $this->db->where('pt.status', 'dikumpulkan');
        $this->db->where('pt.tanggal_kumpul IS NOT NULL');

        $cek = $this->db->get()->row();

        if (!$cek) {
            return false; // tidak valid / bukan milik LPK / belum dikumpulkan
        }

        /* =====================================================
        2. UPDATE NILAI
        ===================================================== */
        $this->db->where('pengumpulan_id', $pengumpulan_id);
        return $this->db->update('pengumpulan_tugas', $data_update);
    }

    /**
     * VERSI ALTERNATIF jika controller memanggil dengan parameter yang berbeda
     * Gunakan salah satu saja, tidak keduanya
     */
    public function update_nilai_v2($pengumpulan_id, $nilai, $feedback, $lpk_id)
    {
        /* =====================================================
        1. VALIDASI: pastikan pengumpulan valid & milik LPK
        ===================================================== */
        $this->db->select('pt.pengumpulan_id');
        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');
        $this->db->where('pt.pengumpulan_id', $pengumpulan_id);
        $this->db->where('k.lpk_id', $lpk_id);

        // WAJIB: hanya boleh menilai yang benar-benar dikumpulkan
        $this->db->where('pt.status', 'dikumpulkan');
        $this->db->where('pt.tanggal_kumpul IS NOT NULL');

        $cek = $this->db->get()->row();

        if (!$cek) {
            return false; // tidak valid / bukan milik LPK / belum dikumpulkan
        }

        /* =====================================================
        2. UPDATE NILAI
        ===================================================== */
        $data_update = [
            'nilai' => $nilai,
            'feedback' => $feedback,
            'status' => 'dinilai',
            'tanggal_penilaian' => date('Y-m-d H:i:s')
        ];

        $this->db->where('pengumpulan_id', $pengumpulan_id);
        return $this->db->update('pengumpulan_tugas', $data_update);
    }

    /**
     * Ambil statistik penilaian per kursus
     */
    public function get_statistik_per_kursus($lpk_id) {
        $this->db->select('
            k.kursus_id,
            k.judul_kursus,
            COUNT(pt.pengumpulan_id) as total_tugas,
            SUM(CASE WHEN pt.status = "dinilai" THEN 1 ELSE 0 END) as sudah_dinilai,
            SUM(CASE WHEN pt.status = "dikumpulkan" THEN 1 ELSE 0 END) as belum_dinilai,
            AVG(CASE WHEN pt.status = "dinilai" THEN pt.nilai ELSE NULL END) as rata_rata_nilai
        ');
        $this->db->from('kursus k');
        $this->db->join('materi_kursus mk', 'k.kursus_id = mk.kursus_id', 'left');
        $this->db->join('tugas t', 'mk.materi_id = t.materi_id', 'left');
        $this->db->join('pengumpulan_tugas pt', 't.tugas_id = pt.tugas_id', 'left');
        $this->db->where('k.lpk_id', $lpk_id);
        $this->db->group_by('k.kursus_id, k.judul_kursus');
        $this->db->order_by('k.judul_kursus', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Ambil statistik penilaian per bulan
     */
    public function get_statistik_per_bulan($lpk_id)
    {
        $this->db->select("
            DATE_FORMAT(pt.tanggal_penilaian, '%Y-%m') AS bulan,
            COUNT(pt.pengumpulan_id) AS total_dinilai,
            ROUND(AVG(pt.nilai), 2) AS rata_rata_nilai,
            MIN(pt.nilai) AS nilai_terendah,
            MAX(pt.nilai) AS nilai_tertinggi
        ");

        $this->db->from('pengumpulan_tugas pt');
        $this->db->join('tugas t', 'pt.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus mk', 't.materi_id = mk.materi_id');
        $this->db->join('kursus k', 'mk.kursus_id = k.kursus_id');

        $this->db->where('k.lpk_id', $lpk_id);
        $this->db->where('pt.status', 'dinilai');
        $this->db->where('pt.tanggal_penilaian IS NOT NULL');

        $this->db->group_by("DATE_FORMAT(pt.tanggal_penilaian, '%Y-%m')");
        $this->db->order_by('bulan', 'DESC');
        $this->db->limit(6);

        return $this->db->get()->result_array();
    }
}