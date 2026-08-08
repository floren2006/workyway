<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_siswa_data($user_id) {
        $this->db->select('u.*, s.*');
        $this->db->from('users u');
        $this->db->join('siswa s', 'u.user_id = s.user_id', 'left');
        $this->db->where('u.user_id', $user_id);
        return $this->db->get()->row();
    }

    public function get_kursus_dengan_progress($user_id) {
        // Dapatkan siswa_id dari user_id
        $siswa = $this->db->select('siswa_id')->from('siswa')->where('user_id', $user_id)->get()->row();
        $siswa_id = $siswa ? $siswa->siswa_id : 0;
        
        if (!$siswa_id) return [];
        
        // Query untuk mendapatkan semua kursus yang diikuti
        $this->db->select('
            k.kursus_id,
            k.judul_kursus,
            k.deskripsi,
            k.biaya,
            k.durasi,
            k.gambar_kursus,
            k.status_kursus,
            e.enrollment_id,
            e.tanggal_daftar,
            e.sertifikat,
            e.nilai,
            e.rating,
            e.review,
            CASE 
                WHEN k.guru_id IS NOT NULL THEN (SELECT nama FROM users WHERE user_id = (SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id))
                WHEN k.instruktur_id IS NOT NULL THEN (SELECT nama FROM instruktur WHERE instruktur_id = k.instruktur_id)
                ELSE (SELECT nama_lembaga FROM lpk WHERE lpk_id = k.lpk_id)
            END as instruktur
        ');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
        $this->db->where('s.user_id', $user_id);
        $this->db->where('k.status_kursus', 'active');
        $this->db->order_by('e.tanggal_daftar', 'DESC');
        
        $all_enrollments = $this->db->get()->result();
        
        // Filter kursus yang masih aktif berdasarkan tanggal (durasi belum habis)
        $kursus_list = [];
        foreach ($all_enrollments as $enrollment) {
            // Kursus dianggap aktif jika:
            // 1. Status di DB adalah 'active'
            // 2. Belum memiliki sertifikat (kursus belum selesai)
            // 3. Masih dalam rentang waktu (durasi belum habis)
            
            if (empty($enrollment->sertifikat) && $this->is_kursus_masih_aktif($enrollment)) {
                $progress_data = $this->get_progress_by_materi($siswa_id, $enrollment->kursus_id);
                $enrollment->progress = $progress_data['progress'];
                $enrollment->total_materi = $progress_data['total_materi'];
                $enrollment->completed_materi = $progress_data['completed_materi'];
                
                // Hitung sisa hari
                $sisa_hari = $this->hitung_sisa_hari_kursus($enrollment);
                $enrollment->sisa_hari = $sisa_hari > 0 ? $sisa_hari : 0;
                
                // Hitung statistik tugas
                $tugas_data = $this->get_tugas_stats($siswa_id, $enrollment->kursus_id);
                $enrollment->total_tugas = $tugas_data['total_tugas'];
                $enrollment->tugas_selesai = $tugas_data['tugas_selesai'];
                
                $kursus_list[] = $enrollment;
            }
        }
        
        return $kursus_list;
    }

    // Cek apakah kursus masih aktif (durasi belum habis)
    public function is_kursus_masih_aktif($kursus) {
        $today = date('Y-m-d');
        
        if (empty($kursus->tanggal_daftar) || empty($kursus->durasi)) {
            return false;
        }
        
        // Hitung tanggal selesai berdasarkan durasi
        $tanggal_selesai = $this->hitung_tanggal_selesai($kursus->tanggal_daftar, $kursus->durasi);
        
        // Kursus masih aktif jika hari ini <= tanggal selesai
        return strtotime($today) <= strtotime($tanggal_selesai);
    }

    // Hitung tanggal selesai berdasarkan durasi
    private function hitung_tanggal_selesai($tanggal_daftar, $durasi) {
        // Parse durasi (contoh: "3 Bulan", "2.5 Bulan", "4 Bulan")
        $durasi = strtolower(trim($durasi));
        
        // Default: 3 bulan jika tidak bisa diparse
        $bulan = 3;
        
        if (strpos($durasi, 'bulan') !== false) {
            // Ambil angka dari string durasi
            preg_match('/(\d+\.?\d*)/', $durasi, $matches);
            if (!empty($matches[1])) {
                $bulan = (float)$matches[1];
            }
        } elseif (strpos($durasi, 'hari') !== false) {
            // Jika durasi dalam hari
            preg_match('/(\d+\.?\d*)/', $durasi, $matches);
            if (!empty($matches[1])) {
                $hari = (int)$matches[1];
                return date('Y-m-d', strtotime($tanggal_daftar . " + $hari days"));
            }
        } elseif (strpos($durasi, 'minggu') !== false) {
            // Jika durasi dalam minggu
            preg_match('/(\d+\.?\d*)/', $durasi, $matches);
            if (!empty($matches[1])) {
                $minggu = (int)$matches[1];
                $hari = $minggu * 7;
                return date('Y-m-d', strtotime($tanggal_daftar . " + $hari days"));
            }
        }
        
        // Hitung tanggal selesai (bulan)
        $bulan_int = (int)$bulan;
        $hari_tambahan = ($bulan - $bulan_int) * 30; // 0.5 bulan = 15 hari
        
        $tanggal_selesai = date('Y-m-d', strtotime($tanggal_daftar . " + $bulan_int months"));
        if ($hari_tambahan > 0) {
            $tanggal_selesai = date('Y-m-d', strtotime($tanggal_selesai . " + $hari_tambahan days"));
        }
        
        return $tanggal_selesai;
    }

    // Hitung sisa hari kursus
    private function hitung_sisa_hari_kursus($kursus) {
        $today = date('Y-m-d');
        $tanggal_selesai = $this->hitung_tanggal_selesai($kursus->tanggal_daftar, $kursus->durasi);
        
        $today_time = strtotime($today);
        $selesai_time = strtotime($tanggal_selesai);
        
        $sisa_hari = ceil(($selesai_time - $today_time) / (60 * 60 * 24));
        return $sisa_hari > 0 ? $sisa_hari : 0;
    }

    // Fungsi untuk menghitung progress berdasarkan materi
    public function get_progress_by_materi($siswa_id, $kursus_id) {
        if (!$siswa_id) {
            return [
                'total_materi' => 0,
                'completed_materi' => 0,
                'progress' => 0
            ];
        }
        
        // Hitung total materi dalam kursus
        $this->db->select('COUNT(*) as total_materi');
        $this->db->from('materi_kursus');
        $this->db->where('kursus_id', $kursus_id);
        $total_result = $this->db->get()->row();
        $total_materi = $total_result ? $total_result->total_materi : 0;
        
        // Hitung materi yang sudah diselesaikan
        $this->db->select('COUNT(*) as completed_materi');
        $this->db->from('progress_materi');
        $this->db->join('materi_kursus', 'progress_materi.materi_id = materi_kursus.materi_id');
        $this->db->where('progress_materi.siswa_id', $siswa_id);
        $this->db->where('materi_kursus.kursus_id', $kursus_id);
        $this->db->where('progress_materi.status', 'completed');
        $completed_result = $this->db->get()->row();
        $completed_materi = $completed_result ? $completed_result->completed_materi : 0;
        
        // Hitung progress dalam persentase
        $progress = ($total_materi > 0) ? round(($completed_materi / $total_materi) * 100) : 0;
        
        return [
            'total_materi' => $total_materi,
            'completed_materi' => $completed_materi,
            'progress' => $progress
        ];
    }

    // Fungsi untuk menghitung statistik tugas
    public function get_tugas_stats($siswa_id, $kursus_id) {
        if (!$siswa_id) {
            return [
                'total_tugas' => 0,
                'tugas_selesai' => 0
            ];
        }
        
        // Hitung total tugas dalam kursus
        $this->db->select('COUNT(*) as total_tugas');
        $this->db->from('tugas t');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->where('m.kursus_id', $kursus_id);
        $total_result = $this->db->get()->row();
        $total_tugas = $total_result ? $total_result->total_tugas : 0;
        
        // Hitung tugas yang sudah dikumpulkan
        $this->db->select('COUNT(DISTINCT p.tugas_id) as tugas_selesai');
        $this->db->from('pengumpulan_tugas p');
        $this->db->join('tugas t', 'p.tugas_id = t.tugas_id');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->where('m.kursus_id', $kursus_id);
        $this->db->where('p.siswa_id', $siswa_id);
        $this->db->where_in('p.status', ['dikumpulkan', 'dinilai']);
        $selesai_result = $this->db->get()->row();
        $tugas_selesai = $selesai_result ? $selesai_result->tugas_selesai : 0;
        
        return [
            'total_tugas' => $total_tugas,
            'tugas_selesai' => $tugas_selesai
        ];
    }

    // Fungsi untuk halaman "Kursus Saya" dengan detail lengkap
    public function get_kursus_saya_detil($user_id) {
        // Dapatkan siswa_id dari user_id
        $siswa = $this->db->select('siswa_id')->from('siswa')->where('user_id', $user_id)->get()->row();
        $siswa_id = $siswa ? $siswa->siswa_id : 0;
        
        if (!$siswa_id) return [];
        
        // Query untuk mendapatkan semua kursus yang diikuti
        $this->db->select('
            k.kursus_id,
            k.judul_kursus,
            k.deskripsi,
            k.durasi,
            k.gambar_kursus,
            e.enrollment_id,
            e.tanggal_daftar,
            e.sertifikat,
            e.nilai,
            e.rating,
            e.review,
            CASE 
                WHEN k.guru_id IS NOT NULL THEN (SELECT nama FROM users WHERE user_id = (SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id))
                WHEN k.instruktur_id IS NOT NULL THEN (SELECT nama FROM instruktur WHERE instruktur_id = k.instruktur_id)
                ELSE (SELECT nama_lembaga FROM lpk WHERE lpk_id = k.lpk_id)
            END as instruktur
        ');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
        $this->db->where('s.user_id', $user_id);
        $this->db->where('k.status_kursus', 'active');
        $this->db->order_by('e.tanggal_daftar', 'DESC');
        
        $all_enrollments = $this->db->get()->result();
        
        $kursus_list = [];
        foreach ($all_enrollments as $enrollment) {
            // Hanya tampilkan kursus yang masih aktif (durasi belum habis) dan belum selesai
            if (empty($enrollment->sertifikat) && $this->is_kursus_masih_aktif($enrollment)) {
                $progress_data = $this->get_progress_by_materi($siswa_id, $enrollment->kursus_id);
                $tugas_data = $this->get_tugas_stats($siswa_id, $enrollment->kursus_id);
                $sisa_hari = $this->hitung_sisa_hari_kursus($enrollment);
                
                $kursus_list[] = (object)[
                    'enrollment_id' => $enrollment->enrollment_id,
                    'kursus_id' => $enrollment->kursus_id,
                    'judul_kursus' => $enrollment->judul_kursus,
                    'deskripsi' => $enrollment->deskripsi,
                    'instruktur' => $enrollment->instruktur,
                    'tanggal_daftar' => $enrollment->tanggal_daftar,
                    'durasi' => $enrollment->durasi,
                    'progress' => $progress_data['progress'],
                    'total_materi' => $progress_data['total_materi'],
                    'completed_materi' => $progress_data['completed_materi'],
                    'total_tugas' => $tugas_data['total_tugas'],
                    'tugas_selesai' => $tugas_data['tugas_selesai'],
                    'sisa_hari' => $sisa_hari,
                    'rating' => $enrollment->rating,
                    'review' => $enrollment->review,
                    'is_completed' => ($progress_data['progress'] == 100)
                ];
            }
        }
        
        return $kursus_list;
    }

    // Hitung statistik kursus
    public function get_kursus_stats($user_id) {
        // Hitung kursus aktif
        $kursus_aktif = $this->get_kursus_saya_detil($user_id);
        $jumlah_kursus_aktif = count($kursus_aktif);
        
        // Hitung sertifikat (kursus yang sudah selesai)
        $siswa = $this->db->select('siswa_id')->from('siswa')->where('user_id', $user_id)->get()->row();
        $siswa_id = $siswa ? $siswa->siswa_id : 0;
        
        $jumlah_sertifikat = 0;
        if ($siswa_id) {
            $this->db->select('COUNT(*) as total_sertifikat');
            $this->db->from('enrollment');
            $this->db->where('siswa_id', $siswa_id);
            $this->db->where('sertifikat IS NOT NULL');
            $result = $this->db->get()->row();
            $jumlah_sertifikat = $result ? $result->total_sertifikat : 0;
        }
        
        return [
            'kursus_aktif' => $jumlah_kursus_aktif,
            'sertifikat' => $jumlah_sertifikat
        ];
    }

    public function get_rekomendasi_kursus($user_id) {
        // Dapatkan siswa_id dari user_id
        $siswa = $this->db->select('siswa_id')->from('siswa')->where('user_id', $user_id)->get()->row();
        $siswa_id = $siswa ? $siswa->siswa_id : 0;
        
        $this->db->select('
            k.kursus_id,
            k.judul_kursus,
            k.biaya,
            k.rating_rata2,
            k.durasi,
            k.gambar_kursus,
            (SELECT COUNT(*) FROM enrollment WHERE kursus_id = k.kursus_id) as total_peserta,
            CASE 
                WHEN k.guru_id IS NOT NULL THEN (SELECT nama FROM users WHERE user_id = (SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id))
                WHEN k.instruktur_id IS NOT NULL THEN (SELECT nama FROM instruktur WHERE instruktur_id = k.instruktur_id)
                ELSE (SELECT nama_lembaga FROM lpk WHERE lpk_id = k.lpk_id)
            END as instruktur
        ');
        $this->db->from('kursus k');
        $this->db->where('k.status_kursus', 'active');
        
        // EKSLUSI: Kursus yang sudah didaftar oleh siswa ini
        if ($siswa_id > 0) {
            $this->db->where("k.kursus_id NOT IN (
                SELECT kursus_id FROM enrollment 
                WHERE siswa_id = $siswa_id
            )");
        }
        
        $this->db->order_by('k.rating_rata2', 'DESC');
        $this->db->limit(3);
        return $this->db->get()->result();
    }

    public function get_jumlah_sertifikat($user_id) {
        $siswa = $this->db->select('siswa_id')->from('siswa')->where('user_id', $user_id)->get()->row();
        if (!$siswa) return 0;
        
        $this->db->select('COUNT(*) as total_sertifikat');
        $this->db->from('enrollment');
        $this->db->where('siswa_id', $siswa->siswa_id);
        $this->db->where('sertifikat IS NOT NULL');
        $result = $this->db->get()->row();
        return $result ? $result->total_sertifikat : 0;
    }

    public function get_notifikasi($user_id) {
        $this->db->select('*');
        $this->db->from('notifikasi');
        $this->db->where('penerima_id', $user_id);
        $this->db->order_by('tanggal', 'DESC');
        $this->db->limit(3);
        return $this->db->get()->result();
    }
}