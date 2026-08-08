<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Menghitung progress berdasarkan tugas yang dikerjakan
 */
function hitung_progress_tugas($kursus_id, $siswa_id) {
    $CI =& get_instance();
    $CI->load->database();
    
    // Hitung total tugas
    $CI->db->select('COUNT(*) as total');
    $CI->db->from('tugas t');
    $CI->db->join('materi_kursus m', 't.materi_id = m.materi_id');
    $CI->db->where('m.kursus_id', $kursus_id);
    $total = $CI->db->get()->row()->total;
    
    // Hitung tugas yang sudah dikumpulkan
    $CI->db->select('COUNT(DISTINCT p.tugas_id) as selesai');
    $CI->db->from('pengumpulan_tugas p');
    $CI->db->join('tugas t', 'p.tugas_id = t.tugas_id');
    $CI->db->join('materi_kursus m', 't.materi_id = m.materi_id');
    $CI->db->where('m.kursus_id', $kursus_id);
    $CI->db->where('p.siswa_id', $siswa_id);
    $CI->db->where_in('p.status', ['dikumpulkan', 'dinilai']);
    $selesai = $CI->db->get()->row()->selesai;
    
    if ($total > 0) {
        $progress = round(($selesai / $total) * 100);
        return [
            'total' => $total,
            'selesai' => $selesai,
            'progress' => $progress,
            'persentase' => $progress . '%'
        ];
    }
    
    return [
        'total' => 0,
        'selesai' => 0,
        'progress' => 0,
        'persentase' => '0%'
    ];
}

/**
 * Mendapatkan status progress dengan warna yang sesuai
 */
function get_status_progress($progress) {
    if ($progress == 0) {
        return ['status' => 'Belum mulai', 'color' => '#e5e7eb'];
    } elseif ($progress < 30) {
        return ['status' => 'Baru dimulai', 'color' => '#fbbf24'];
    } elseif ($progress < 70) {
        return ['status' => 'Sedang berjalan', 'color' => '#3b82f6'];
    } elseif ($progress < 100) {
        return ['status' => 'Hampir selesai', 'color' => '#10b981'];
    } else {
        return ['status' => 'Selesai', 'color' => '#8b5cf6'];
    }
}