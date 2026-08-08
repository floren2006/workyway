<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get Popular Courses - FIXED SQL SYNTAX
     */
    public function get_popular_kursus($limit = 8)
    {
        // Gunakan query manual untuk CASE statement yang kompleks
        $sql = "
        SELECT 
            k.*, 
            kk.nama_kategori,
            COALESCE(
                u1.nama, 
                u2.nama, 
                'Instruktur Tidak Tersedia'
            ) as nama_instruktur
        FROM kursus k
        LEFT JOIN kategori_kursus kk ON k.kategori_id = kk.kategori_id
        LEFT JOIN lpk l ON k.lpk_id = l.lpk_id
        LEFT JOIN users u1 ON l.user_id = u1.user_id
        LEFT JOIN guru_freelance g ON k.guru_id = g.guru_id
        LEFT JOIN users u2 ON g.user_id = u2.user_id
        WHERE k.status_kursus = 'active'
        ORDER BY k.rating_rata2 DESC
        LIMIT ?
        ";
        
        return $this->db->query($sql, array($limit))->result();
    }

    /**
     * Get Popular Courses - ALTERNATIF 2: Menggunakan IF Statement
     */
    public function get_popular_kursus_simple($limit = 8)
    {
        $this->db->select('k.*, kk.nama_kategori');
        $this->db->select("
            IF(k.lpk_id IS NOT NULL, 
                (SELECT nama FROM users WHERE user_id = (SELECT user_id FROM lpk WHERE lpk_id = k.lpk_id)),
                IF(k.guru_id IS NOT NULL,
                    (SELECT nama FROM users WHERE user_id = (SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id)),
                    'Instruktur Tidak Tersedia'
                )
            ) as nama_instruktur
        ", FALSE);
        
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->where('k.status_kursus', 'active');
        $this->db->order_by('k.rating_rata2', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Get Popular Courses - ALTERNATIF 3: Query Builder tanpa CASE
     */
    public function get_popular_kursus_alt($limit = 8)
    {
        // Ambil semua data terlebih dahulu
        $this->db->select('k.*, kk.nama_kategori, l.user_id as lpk_user_id, g.user_id as guru_user_id');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->join('lpk l', 'k.lpk_id = l.lpk_id', 'left');
        $this->db->join('guru_freelance g', 'k.guru_id = g.guru_id', 'left');
        $this->db->where('k.status_kursus', 'active');
        $this->db->order_by('k.rating_rata2', 'DESC');
        $this->db->limit($limit);
        
        $courses = $this->db->get()->result();
        return $courses;
    
    }
    /**
 * Get Popular Courses berdasarkan JUMLAH PESERTA
 */
public function get_popular_kursus_by_enrollment($limit = 4)
{
    $sql = "
        SELECT 
            k.*,
            kk.nama_kategori,
            COUNT(e.enrollment_id) AS total_peserta,
            COALESCE(
                u1.nama,
                u2.nama,
                'Instruktur Tidak Tersedia'
            ) AS nama_instruktur
        FROM kursus k
        LEFT JOIN enrollment e ON e.kursus_id = k.kursus_id
        LEFT JOIN kategori_kursus kk ON k.kategori_id = kk.kategori_id
        LEFT JOIN lpk l ON k.lpk_id = l.lpk_id
        LEFT JOIN users u1 ON l.user_id = u1.user_id
        LEFT JOIN guru_freelance g ON k.guru_id = g.guru_id
        LEFT JOIN users u2 ON g.user_id = u2.user_id
        WHERE k.status_kursus = 'active'
        GROUP BY k.kursus_id
        ORDER BY total_peserta DESC, k.rating_rata2 DESC
        LIMIT ?
    ";

    return $this->db->query($sql, [$limit])->result();
}


    /**
     * Count Total Courses
     */
    public function count_total_courses()
    {
        return $this->db->where('status_kursus', 'active')
                        ->count_all_results('kursus');
    }

    /**
     * Get Testimonials
     */
    public function get_testimonials($limit = 3)
    {
        $this->db->select('e.review, e.rating, u.nama as nama_siswa, k.judul_kursus, k.kursus_id');
        $this->db->from('enrollment e');
        $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
        $this->db->join('users u', 's.user_id = u.user_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('e.review IS NOT NULL');
        $this->db->where('e.review !=', '');
        $this->db->order_by('e.tanggal_daftar', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

     /* Get All Instructors (Guru + LPK) - PERBAIKI
     */
    public function get_all_instructors($limit = 8)
    {
        // Query yang lebih sederhana untuk MySQL
        $sql = "
        (
            SELECT 
                'guru' as tipe,
                g.guru_id as id,
                u.nama,
                u.foto_profil,
                g.keahlian,
                g.rating_rata2,
                g.pengalaman,
                (SELECT COUNT(*) FROM kursus WHERE guru_id = g.guru_id AND status_kursus = 'active') as total_kursus
            FROM guru_freelance g
            JOIN users u ON g.user_id = u.user_id
            WHERE g.status_verifikasi = 'approved'
        )
        UNION
        (
            SELECT 
                'lpk' as tipe,
                l.lpk_id as id,
                l.nama_lembaga as nama,
                u.foto_profil,
                'LPK Terverifikasi' as keahlian,
                5.0 as rating_rata2,
                l.deskripsi as pengalaman,
                (SELECT COUNT(*) FROM kursus WHERE lpk_id = l.lpk_id AND status_kursus = 'active') as total_kursus
            FROM lpk l
            JOIN users u ON l.user_id = u.user_id
            WHERE l.status_verifikasi = 'approved'
        )
        ORDER BY rating_rata2 DESC
        LIMIT ?
        ";
        
        return $this->db->query($sql, array($limit))->result();
    }

    /**
     * Get Verified LPK
     */
    public function get_verified_lpk($limit = 3)
    {
        $this->db->select('l.nama_lembaga, l.deskripsi, l.nomor_izin, u.foto_profil, 
            (SELECT COUNT(*) FROM kursus WHERE lpk_id = l.lpk_id AND status_kursus = "active") as total_kursus');
        $this->db->from('lpk l');
        $this->db->join('users u', 'l.user_id = u.user_id');
        $this->db->where('l.status_verifikasi', 'approved');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Count Total Students
     */
    public function count_total_students()
    {
        return $this->db->where('role', 'siswa')
                        ->where('status_aktif', 1)
                        ->count_all_results('users');
    }

    /**
     * Count Total Instructors (Guru + LPK)
     */
    public function count_total_instructors()
    {
        // Hitung guru freelance yang approved
        $guru = $this->db->where('status_verifikasi', 'approved')
                         ->count_all_results('guru_freelance');
        
        // Hitung LPK yang approved
        $lpk = $this->db->where('status_verifikasi', 'approved')
                        ->count_all_results('lpk');
        
        return $guru + $lpk;
    }

    /**
     * Count Total LPK
     */
    public function count_total_lpk()
    {
        return $this->db->where('status_verifikasi', 'approved')
                        ->count_all_results('lpk');
    }

    /**
     * Get Courses by Category
     */
    public function get_courses_by_category($kategori_id, $limit = 4)
    {
        // Query sederhana untuk menghindari CASE yang bermasalah
        $sql = "
        SELECT 
            k.*, 
            kk.nama_kategori,
            CASE
                WHEN k.lpk_id IS NOT NULL THEN 
                    (SELECT nama FROM users WHERE user_id = (SELECT user_id FROM lpk WHERE lpk_id = k.lpk_id))
                WHEN k.guru_id IS NOT NULL THEN 
                    (SELECT nama FROM users WHERE user_id = (SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id))
                ELSE 'Instruktur Tidak Tersedia'
            END as nama_instruktur
        FROM kursus k
        LEFT JOIN kategori_kursus kk ON k.kategori_id = kk.kategori_id
        WHERE k.kategori_id = ? 
        AND k.status_kursus = 'active'
        ORDER BY k.rating_rata2 DESC
        LIMIT ?
        ";
        
        return $this->db->query($sql, array($kategori_id, $limit))->result();
    }

    public function get_categories()
    {
        return $this->db->get('kategori_kursus')->result();
    }

}