<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /* ===============================
       KURSUS
    =============================== */
    public function get_kursus_by_id($kursus_id) {
        return $this->db->select('k.*, kat.nama_kategori')
            ->from('kursus k')
            ->join('kategori_kursus kat', 'kat.kategori_id = k.kategori_id', 'left')
            ->where('k.kursus_id', $kursus_id)
            ->get()
            ->row();
    }

    /* ===============================
       USER & SISWA
    =============================== */
    public function get_user_by_id($user_id) {
        return $this->db->get_where('users', [
            'user_id' => $user_id
        ])->row();
    }

    public function get_or_create_siswa($user_id) {
        $siswa = $this->db->get_where('siswa', [
            'user_id' => $user_id
        ])->row();

        if ($siswa) {
            return $siswa->siswa_id;
        }

        // ambil data user
        $user = $this->db->get_where('users', [
            'user_id' => $user_id
        ])->row();

        if (!$user) {
            return null;
        }

        // auto buat siswa
        $this->db->insert('siswa', [
            'user_id' => $user_id,
            'nama_lengkap' => $user->username, // atau bisa diganti dengan field lain
            'email' => $user->email,
            'tanggal_daftar' => date('Y-m-d H:i:s')
        ]);

        return $this->db->insert_id();
    }

    /* ===============================
       ENROLLMENT
    =============================== */
    public function create_enrollment($user_id, $kursus_id) {
        $siswa_id = $this->get_or_create_siswa($user_id);

        if (!$siswa_id) {
            return false;
        }

        // Cegah daftar dua kali
        $existing = $this->db->get_where('enrollment', [
            'siswa_id' => $siswa_id,
            'kursus_id' => $kursus_id
        ])->row();

        if ($existing) {
            return $existing->enrollment_id;
        }

        $data = [
            'kursus_id'       => $kursus_id,
            'siswa_id'        => $siswa_id,
            'tanggal_daftar'  => date('Y-m-d H:i:s'),
            'nilai'           => null,
            'rating'          => null,
            'review'          => null,
            'sertifikat'      => null,
        ];

        $this->db->insert('enrollment', $data);
        return $this->db->insert_id();
    }

    public function get_enrollment_by_id($enrollment_id) {
        return $this->db->get_where('enrollment', [
            'enrollment_id' => $enrollment_id
        ])->row();
    }

    public function get_enrollment_by_kursus_siswa($kursus_id, $siswa_id) {
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('siswa_id', $siswa_id);
        return $this->db->get('enrollment')->row();
    }

    // ===== FUNGSI BARU UNTUK RATING & REVIEW =====
   
    /**
     * Update rating dan review pada enrollment
     */
    public function update_rating_review($enrollment_id, $rating, $review) {
        $data = [
            'rating' => $rating,
            'review' => $review,
        ];
       
        $this->db->where('enrollment_id', $enrollment_id);
        return $this->db->update('enrollment', $data);
    }

    /**
     * Get rating statistics for a course
     */
    public function get_rating_stats($kursus_id) {
        $this->db->select('
            AVG(rating) as avg_rating,
            COUNT(rating) as total_reviews,
            SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
            SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
            SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
            SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1
        ');
        $this->db->from('enrollment');
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('rating IS NOT NULL', null, false);
        return $this->db->get()->row();
    }

    /**
     * Get all reviews for a course
     */
    public function get_all_reviews($kursus_id, $limit = 10, $offset = 0) {
        $this->db->select('enrollment.*, siswa.nama_lengkap, siswa.foto_profil');
        $this->db->from('enrollment');
        $this->db->join('siswa', 'siswa.siswa_id = enrollment.siswa_id');
        $this->db->where('enrollment.kursus_id', $kursus_id);
        $this->db->where('enrollment.review IS NOT NULL', null, false);
        $this->db->where('enrollment.rating IS NOT NULL', null, false);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    /**
     * Get recent reviews across all courses
     */
    public function get_recent_reviews($limit = 5) {
        $this->db->select('enrollment.*, siswa.nama_lengkap, siswa.foto_profil, kursus.judul_kursus');
        $this->db->from('enrollment');
        $this->db->join('siswa', 'siswa.siswa_id = enrollment.siswa_id');
        $this->db->join('kursus', 'kursus.kursus_id = enrollment.kursus_id');
        $this->db->where('enrollment.review IS NOT NULL', null, false);
        $this->db->where('enrollment.rating IS NOT NULL', null, false);
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * Get enrollment with rating status
     */
    public function get_enrollment_with_rating($enrollment_id) {
        $this->db->select('enrollment.*, kursus.judul_kursus, siswa.nama_lengkap');
        $this->db->from('enrollment');
        $this->db->join('kursus', 'kursus.kursus_id = enrollment.kursus_id');
        $this->db->join('siswa', 'siswa.siswa_id = enrollment.siswa_id');
        $this->db->where('enrollment.enrollment_id', $enrollment_id);
        return $this->db->get()->row();
    }

    /**
     * Check if student has completed course (all materials)
     */
    public function is_course_completed($kursus_id, $siswa_id) {
        // Hitung total materi dalam kursus
        $this->db->select('COUNT(*) as total_materi');
        $this->db->from('materi');
        $this->db->where('kursus_id', $kursus_id);
        $total_materi = $this->db->get()->row()->total_materi;
       
        if ($total_materi == 0) {
            return false;
        }
       
        // Hitung materi yang sudah diselesaikan
        $this->db->select('COUNT(*) as completed_materi');
        $this->db->from('progress_materi');
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('status', 'completed');
        $completed_materi = $this->db->get()->row()->completed_materi;
       
        return ($completed_materi == $total_materi);
    }

    /**
     * Get courses completed but not rated by student
     */
    public function get_completed_unrated_courses($siswa_id) {
        $this->db->select('enrollment.*, kursus.judul_kursus, kursus.deskripsi, kursus.gambar');
        $this->db->from('enrollment');
        $this->db->join('kursus', 'kursus.kursus_id = enrollment.kursus_id');
        $this->db->where('enrollment.siswa_id', $siswa_id);
        $this->db->where('enrollment.rating IS NULL', null, false);
        $this->db->where('enrollment.review IS NULL', null, false);
       
        // Get all enrollments and filter by completion status
        $enrollments = $this->db->get()->result();
        $completed_courses = [];
       
        foreach ($enrollments as $enrollment) {
            if ($this->is_course_completed($enrollment->kursus_id, $siswa_id)) {
                $completed_courses[] = $enrollment;
            }
        }
       
        return $completed_courses;
    }

    /**
     * Get student's rating for a course
     */
    public function get_student_rating($kursus_id, $siswa_id) {
        $this->db->select('rating, review, tanggal_rating');
        $this->db->from('enrollment');
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('rating IS NOT NULL', null, false);
        return $this->db->get()->row();
    }

    /**
     * Get enrollment progress for dashboard
     */
    public function get_enrollment_progress($siswa_id) {
        $this->db->select('
            e.*,
            k.judul_kursus,
            k.deskripsi,
            k.gambar,
            k.instructor_id,
            (SELECT COUNT(*) FROM materi m WHERE m.kursus_id = e.kursus_id) as total_materi,
            (SELECT COUNT(*) FROM progress_materi pm
             WHERE pm.kursus_id = e.kursus_id
             AND pm.siswa_id = e.siswa_id
             AND pm.status = "completed") as completed_materi
        ');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->where('e.siswa_id', $siswa_id);
        $this->db->order_by('e.tanggal_daftar', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get top rated courses
     */
    public function get_top_rated_courses($limit = 5) {
        $this->db->select('
            k.kursus_id,
            k.judul_kursus,
            k.deskripsi,
            k.gambar,
            k.biaya,
            AVG(e.rating) as avg_rating,
            COUNT(e.rating) as total_ratings
        ');
        $this->db->from('kursus k');
        $this->db->join('enrollment e', 'e.kursus_id = k.kursus_id AND e.rating IS NOT NULL', 'left');
        $this->db->group_by('k.kursus_id');
        $this->db->having('avg_rating IS NOT NULL', null, false);
        $this->db->order_by('avg_rating', 'DESC');
        $this->db->order_by('total_ratings', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /* ===============================
       TRANSAKSI (MIDTRANS CALLBACK)
    =============================== */
    public function insert_transaksi($data) {
        return $this->db->insert('transaksi', [
            'enrollment_id'      => $data['enrollment_id'],
            'jumlah'             => $data['jumlah'],
            'metode_pembayaran'  => $data['metode_pembayaran'],
            'status'             => $data['status'],
            'tanggal_transaksi'  => date('Y-m-d H:i:s'),
            'gaji'               => $data['gaji']
        ]);
    }

    public function update_status_transaksi($enrollment_id, $status) {
        return $this->db->where('enrollment_id', $enrollment_id)
            ->update('transaksi', [
                'status' => $status
            ]);
    }

    public function get_transaksi_by_enrollment($enrollment_id) {
        return $this->db->get_where('transaksi', [
            'enrollment_id' => $enrollment_id
        ])->row();
    }

    /* ===============================
       NOTIFIKASI
    =============================== */
    public function insert_notifikasi($user_id, $judul, $isi) {
        return $this->db->insert('notifikasi', [
            'penerima_id' => $user_id,
            'judul'       => $judul,
            'isi'         => $isi,
            'tanggal'     => date('Y-m-d H:i:s'),
            'status'      => 'unread'
        ]);
    }

    public function create_enrollment_pending($user_id, $kursus_id) {
        $siswa_id = $this->get_or_create_siswa($user_id);
        $kursus   = $this->get_kursus_by_id($kursus_id);

        // INSERT ENROLLMENT
        $this->db->insert('enrollment', [
            'kursus_id' => $kursus_id,
            'siswa_id'  => $siswa_id,
            'tanggal_daftar' => date('Y-m-d H:i:s')
        ]);

        $enrollment_id = $this->db->insert_id();

        // HITUNG BIAYA
        $pajak = 0.1 * $kursus->biaya;
        $total = $kursus->biaya + $pajak + 50000;
        $gaji  = $kursus->biaya * 0.8;

        // INSERT TRANSAKSI
        $this->db->insert('transaksi', [
            'enrollment_id' => $enrollment_id,
            'jumlah' => $total,
            'metode_pembayaran' => 'midtrans',
            'status' => 'pending',
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'gaji' => $gaji
        ]);

        return $enrollment_id;
    }
}
