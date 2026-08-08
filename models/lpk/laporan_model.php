<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // 🔥 INI KUNCINYA
    }

    public function get_summary($lpk_id)
    {
        return $this->db->select('
                COUNT(DISTINCT e.enrollment_id) as total_peserta,
                AVG(CASE WHEN e.nilai IS NOT NULL THEN e.nilai END) as avg_nilai,
                AVG(CASE WHEN e.rating IS NOT NULL AND e.rating > 0 THEN e.rating END) as avg_rating
            ')
            ->from('enrollment e')
            ->join('kursus k', 'k.kursus_id = e.kursus_id')
            ->where('k.lpk_id', $lpk_id)
            ->get()
            ->row_array();
    }


    public function statistik_per_kursus($lpk_id)
    {
        return $this->db->select('
                k.judul_kursus,
                COUNT(DISTINCT e.enrollment_id) as peserta,
                ROUND(AVG(CASE WHEN e.nilai IS NOT NULL THEN e.nilai END), 1) as avg_nilai,
                ROUND(AVG(CASE WHEN e.rating IS NOT NULL AND e.rating > 0 THEN e.rating END), 1) as avg_rating
            ')
            ->from('kursus k')
            ->join('enrollment e', 'e.kursus_id = k.kursus_id', 'left')
            ->where('k.lpk_id', $lpk_id)
            ->group_by('k.kursus_id')
            ->get()
            ->result_array();
    }

    public function get_avg_growth($lpk_id, $field = 'nilai')
{
    // Bulan ini
    $bulan_ini = $this->db->query("
        SELECT AVG($field) AS avg
        FROM enrollment e
        JOIN kursus k ON k.kursus_id = e.kursus_id
        WHERE k.lpk_id = ?
        AND MONTH(e.tanggal_daftar) = MONTH(CURDATE())
        AND YEAR(e.tanggal_daftar) = YEAR(CURDATE())
    ", [$lpk_id])->row()->avg ?? 0;

    // Bulan lalu
    $bulan_lalu = $this->db->query("
        SELECT AVG($field) AS avg
        FROM enrollment e
        JOIN kursus k ON k.kursus_id = e.kursus_id
        WHERE k.lpk_id = ?
        AND MONTH(e.tanggal_daftar) = MONTH(CURDATE() - INTERVAL 1 MONTH)
        AND YEAR(e.tanggal_daftar) = YEAR(CURDATE() - INTERVAL 1 MONTH)
    ", [$lpk_id])->row()->avg ?? 0;

    // Jika bulan lalu 0 → tidak bisa hitung growth
    if ($bulan_lalu == 0) {
        return 0;
    }

    $percent = (($bulan_ini - $bulan_lalu) / $bulan_lalu) * 100;

    return round($percent, 1);
}



   public function ulasan_terbaru($lpk_id, $limit = 5)
    {
        return $this->db->select('
                u.nama AS nama_siswa,
                k.judul_kursus,
                e.review,
                e.rating,
                e.kursus_id
            ')
            ->from('enrollment e')
            ->join('siswa s', 's.siswa_id = e.siswa_id')      // 🔥 WAJIB
            ->join('users u', 'u.user_id = s.user_id')        // 🔥 WAJIB
            ->join('kursus k', 'k.kursus_id = e.kursus_id')
            ->where('k.lpk_id', $lpk_id)
            ->where('e.review IS NOT NULL')
            ->where('e.review != ""')
            ->order_by('e.enrollment_id', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    public function get_total_peserta_growth()
{
    // Bulan ini
    $bulan_ini = $this->db->query("
        SELECT COUNT(*) AS total
        FROM enrollment
        WHERE MONTH(tanggal_daftar) = MONTH(CURDATE())
        AND YEAR(tanggal_daftar) = YEAR(CURDATE())
    ")->row()->total;

    // Bulan lalu
    $bulan_lalu = $this->db->query("
        SELECT COUNT(*) AS total
        FROM enrollment
        WHERE MONTH(tanggal_daftar) = MONTH(CURDATE() - INTERVAL 1 MONTH)
        AND YEAR(tanggal_daftar) = YEAR(CURDATE() - INTERVAL 1 MONTH)
    ")->row()->total;

    $percent = ($bulan_lalu == 0) 
        ? 0 
        : (($bulan_ini - $bulan_lalu) / $bulan_lalu) * 100;

    return [
        'current' => $bulan_ini,
        'percent' => round($percent, 1)
    ];

}

}
