<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_siswa extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // =============== FUNGSI UMUM ===============
    
    public function get_user_by_id($user_id) {
        return $this->db->get_where('users', ['user_id' => $user_id])->row();
    }
    
    public function get_siswa_by_user_id($user_id) {
        return $this->db->get_where('siswa', ['user_id' => $user_id])->row();
    }
    
    public function update_user($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }
    
    // =============== FUNGSI SERTIFIKAT & RIIWAYAT ===============
    
    public function get_sertifikat_by_siswa($siswa_id) {
        $this->db->select('e.*, k.judul_kursus, k.durasi, k.tanggal_dibuat');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('e.siswa_id', $siswa_id);
        $this->db->order_by('e.tanggal_daftar', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_riwayat_kursus($siswa_id) {
        $this->db->select('e.*, k.judul_kursus, k.durasi, k.tanggal_dibuat');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('e.siswa_id', $siswa_id);
        $this->db->order_by('e.tanggal_daftar', 'DESC');
        return $this->db->get()->result();
    }
    
    // =============== FUNGSI PERHITUNGAN NILAI & PROGRESS ===============
    
    /**
     * Hitung progress kursus berdasarkan materi yang diselesaikan
     */
    public function get_progress_kursus($enrollment_id, $siswa_id) {
        // Dapatkan kursus_id
        $this->db->select('kursus_id');
        $this->db->from('enrollment');
        $this->db->where('enrollment_id', $enrollment_id);
        $enrollment = $this->db->get()->row();
        
        if (!$enrollment) return 0;
        
        $kursus_id = $enrollment->kursus_id;
        
        // Hitung total materi
        $this->db->where('kursus_id', $kursus_id);
        $total_materi = $this->db->count_all_results('materi_kursus');
        
        if ($total_materi == 0) return 0;
        
        // Hitung materi yang sudah diselesaikan
        $this->db->select('COUNT(*) as completed');
        $this->db->from('progress_materi pm');
        $this->db->join('materi_kursus mk', 'pm.materi_id = mk.materi_id');
        $this->db->where('mk.kursus_id', $kursus_id);
        $this->db->where('pm.siswa_id', $siswa_id);
        $this->db->where('pm.status', 'completed');
        $result = $this->db->get()->row();
        
        $completed = $result ? (int)$result->completed : 0;
        
        return ($total_materi > 0) ? round(($completed / $total_materi) * 100) : 0;
    }
    
    /**
     * Hitung nilai akhir dari semua tugas (termasuk yang belum dikerjakan = 0)
     */
    public function get_nilai_akhir_kursus($enrollment_id, $siswa_id) {
        // Dapatkan kursus_id
        $this->db->select('kursus_id');
        $this->db->from('enrollment');
        $this->db->where('enrollment_id', $enrollment_id);
        $enrollment = $this->db->get()->row();
        
        if (!$enrollment) return 0;
        
        $kursus_id = $enrollment->kursus_id;
        
        // Hitung total tugas dalam kursus
        $this->db->select('COUNT(DISTINCT t.tugas_id) as total_tugas');
        $this->db->from('materi_kursus mk');
        $this->db->join('tugas t', 'mk.materi_id = t.materi_id');
        $this->db->where('mk.kursus_id', $kursus_id);
        $total_result = $this->db->get()->row();
        $total_tugas = $total_result ? (int)$total_result->total_tugas : 0;
        
        if ($total_tugas == 0) return 0;
        
        // Hitung total nilai dari tugas yang sudah dinilai
        $this->db->select('SUM(p.nilai) as total_nilai, COUNT(p.pengumpulan_id) as tugas_dinilai');
        $this->db->from('materi_kursus mk');
        $this->db->join('tugas t', 'mk.materi_id = t.materi_id');
        $this->db->join('pengumpulan_tugas p', 't.tugas_id = p.tugas_id AND p.siswa_id = ' . $siswa_id);
        $this->db->where('mk.kursus_id', $kursus_id);
        $this->db->where('p.nilai IS NOT NULL');
        $nilai_result = $this->db->get()->row();
        
        $total_nilai = $nilai_result && $nilai_result->total_nilai !== null ? (float)$nilai_result->total_nilai : 0;
        $tugas_dinilai = $nilai_result ? (int)$nilai_result->tugas_dinilai : 0;
        
        // Tugas yang belum dinilai dianggap 0
        $tugas_belum_dinilai = $total_tugas - $tugas_dinilai;
        
        // Hitung nilai akhir: (total nilai yang ada + (tugas belum dinilai * 0)) / total tugas
        $nilai_akhir = ($total_nilai + ($tugas_belum_dinilai * 0)) / $total_tugas;
        
        return round($nilai_akhir, 2);
    }
    
    /**
     * Cek apakah kursus sudah selesai (berdasarkan tanggal)
     */
    public function is_kursus_selesai($enrollment_id) {
        $this->db->select('e.tanggal_daftar, k.durasi');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('e.enrollment_id', $enrollment_id);
        $result = $this->db->get()->row();
        
        if (!$result) return false;
        
        // Hitung tanggal selesai
        $tanggal_mulai = strtotime($result->tanggal_daftar);
        $durasi_bulan = 3; // default
        
        if (preg_match('/(\d+)/', $result->durasi, $matches)) {
            $durasi_bulan = intval($matches[1]);
        }
        
        $tanggal_selesai = strtotime("+{$durasi_bulan} months", $tanggal_mulai);
        $sekarang = time();
        
        return $sekarang >= $tanggal_selesai;
    }
    
    /**
     * Hitung tanggal selesai kursus
     */
    public function get_tanggal_selesai_kursus($enrollment_id) {
        $this->db->select('e.tanggal_daftar, k.durasi');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('e.enrollment_id', $enrollment_id);
        $result = $this->db->get()->row();
        
        if (!$result) return null;
        
        $tanggal_mulai = strtotime($result->tanggal_daftar);
        $durasi_bulan = 3; // default
        
        if (preg_match('/(\d+)/', $result->durasi, $matches)) {
            $durasi_bulan = intval($matches[1]);
        }
        
        return date('Y-m-d', strtotime("+{$durasi_bulan} months", $tanggal_mulai));
    }
    
    /**
     * Cek apakah bisa download sertifikat
     * Syarat: Kursus sudah selesai DAN progress minimal 80%
     */
    public function bisa_download_sertifikat($enrollment_id, $siswa_id) {
        // Cek apakah kursus sudah selesai
        if (!$this->is_kursus_selesai($enrollment_id)) {
            return false;
        }
        
        // Cek progress minimal 80%
        $progress = $this->get_progress_kursus($enrollment_id, $siswa_id);
        return $progress >= 80;
    }
    
    /**
     * Update nama file sertifikat di database
     */
    public function update_sertifikat_filename($enrollment_id, $filename) {
        $this->db->where('enrollment_id', $enrollment_id);
        return $this->db->update('enrollment', ['sertifikat' => $filename]);
    }
    
    // =============== FUNGSI GENERATE SERTIFIKAT ===============
    
    /**
     * Generate data untuk sertifikat
     */
    public function get_data_sertifikat($enrollment_id, $siswa_id) {
        $data = [];
        
        // Data dasar
        $this->db->select('
            e.enrollment_id,
            e.tanggal_daftar,
            e.sertifikat,
            k.judul_kursus,
            k.durasi,
            u.nama as nama_siswa
        ');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
        $this->db->join('users u', 's.user_id = u.user_id');
        $this->db->where('e.enrollment_id', $enrollment_id);
        $this->db->where('e.siswa_id', $siswa_id);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $data = (array) $result;
            
            // Tambahkan data perhitungan
            $data['progress'] = $this->get_progress_kursus($enrollment_id, $siswa_id);
            $data['nilai_akhir'] = $this->get_nilai_akhir_kursus($enrollment_id, $siswa_id);
            $data['tanggal_selesai'] = $this->get_tanggal_selesai_kursus($enrollment_id);
            $data['bisa_download'] = $this->bisa_download_sertifikat($enrollment_id, $siswa_id);
            
            // Info tugas
            $data['info_tugas'] = $this->get_info_tugas($enrollment_id, $siswa_id);
            
            // Kategori nilai
            $data['kategori'] = $this->get_kategori_nilai($data['nilai_akhir'], $data['progress']);
            $data['deskripsi'] = $this->get_deskripsi_sertifikat($data['nilai_akhir'], $data['progress'], $data['info_tugas']['tugas_dinilai']);
        }
        
        return $data;
    }
    
    /**
     * Get info tugas untuk sertifikat
     */
    private function get_info_tugas($enrollment_id, $siswa_id) {
        // Dapatkan kursus_id
        $this->db->select('kursus_id');
        $this->db->from('enrollment');
        $this->db->where('enrollment_id', $enrollment_id);
        $enrollment = $this->db->get()->row();
        
        if (!$enrollment) {
            return [
                'total_tugas' => 0, 
                'tugas_dinilai' => 0, 
                'rata_nilai_dinilai' => 0
            ];
        }
        
        $kursus_id = $enrollment->kursus_id;
        
        // Total tugas
        $this->db->select('COUNT(DISTINCT t.tugas_id) as total_tugas');
        $this->db->from('materi_kursus mk');
        $this->db->join('tugas t', 'mk.materi_id = t.materi_id');
        $this->db->where('mk.kursus_id', $kursus_id);
        $total = $this->db->get()->row();
        
        // Tugas yang sudah dinilai
        $this->db->select('COUNT(p.pengumpulan_id) as tugas_dinilai, AVG(p.nilai) as rata_nilai');
        $this->db->from('materi_kursus mk');
        $this->db->join('tugas t', 'mk.materi_id = t.materi_id');
        $this->db->join('pengumpulan_tugas p', 't.tugas_id = p.tugas_id AND p.siswa_id = ' . $siswa_id);
        $this->db->where('mk.kursus_id', $kursus_id);
        $this->db->where('p.nilai IS NOT NULL');
        $dinilai = $this->db->get()->row();
        
        $rata_nilai = 0;
        if ($dinilai && $dinilai->rata_nilai !== null && $dinilai->tugas_dinilai > 0) {
            $rata_nilai = (float)$dinilai->rata_nilai;
        }
        
        return [
            'total_tugas' => $total ? (int)$total->total_tugas : 0,
            'tugas_dinilai' => $dinilai ? (int)$dinilai->tugas_dinilai : 0,
            'rata_nilai_dinilai' => round($rata_nilai, 2)
        ];
    }
    
    /**
     * Get kategori berdasarkan nilai
     */
    private function get_kategori_nilai($nilai, $progress) {
        // Pastikan nilai dalam bentuk float dan progress integer
        $nilai = (float)$nilai;
        $progress = (int)$progress;
        
        if ($progress < 80) {
            return [
                'nama' => 'BELUM LULUS',
                'grade' => 'T',
                'color' => '#6c757d',
                'icon' => 'fas fa-times-circle',
                'deskripsi' => 'Progress belum memenuhi syarat'
            ];
        }
        
        if ($nilai >= 90) {
            return [
                'nama' => 'PRESTASI TERBAIK',
                'grade' => 'A+',
                'color' => '#28a745',
                'icon' => 'fas fa-trophy',
                'deskripsi' => 'Pencapaian Luar Biasa'
            ];
        } elseif ($nilai >= 85) {
            return [
                'nama' => 'PRESTASI SANGAT BAIK',
                'grade' => 'A',
                'color' => '#20c997',
                'icon' => 'fas fa-medal',
                'deskripsi' => 'Pencapaian Sangat Memuaskan'
            ];
        } elseif ($nilai >= 80) {
            return [
                'nama' => 'PRESTASI BAIK',
                'grade' => 'B+',
                'color' => '#17a2b8',
                'icon' => 'fas fa-award',
                'deskripsi' => 'Pencapaian Memuaskan'
            ];
        } elseif ($nilai >= 75) {
            return [
                'nama' => 'KOMPETEN',
                'grade' => 'B',
                'color' => '#007bff',
                'icon' => 'fas fa-star',
                'deskripsi' => 'Telah menguasai kompetensi'
            ];
        } elseif ($nilai >= 70) {
            return [
                'nama' => 'CUKUP KOMPETEN',
                'grade' => 'C+',
                'color' => '#6f42c1',
                'icon' => 'fas fa-check-circle',
                'deskripsi' => 'Memiliki kemampuan dasar yang cukup'
            ];
        } elseif ($nilai >= 65) {
            return [
                'nama' => 'LULUS',
                'grade' => 'C',
                'color' => '#fd7e14',
                'icon' => 'fas fa-certificate',
                'deskripsi' => 'Telah menyelesaikan program'
            ];
        } else {
            return [
                'nama' => 'LULUS BERSYARAT',
                'grade' => 'D',
                'color' => '#dc3545',
                'icon' => 'fas fa-user-graduate',
                'deskripsi' => 'Telah mengikuti program pembelajaran'
            ];
        }
    }
    
    /**
     * Get deskripsi sertifikat
     */
    private function get_deskripsi_sertifikat($nilai, $progress, $tugas_dinilai) {
        $nilai = (float)$nilai;
        $progress = (int)$progress;
        $tugas_dinilai = (int)$tugas_dinilai;
        
        if ($progress < 80) {
            return "Siswa telah mengikuti kursus namun belum memenuhi syarat minimal penyelesaian materi (80%). Progress saat ini: {$progress}%. Silakan selesaikan lebih banyak materi untuk mendapatkan sertifikat.";
        }
        
        if ($nilai >= 90) {
            return "PRESTASI LUAR BIASA! Siswa telah menyelesaikan kursus dengan nilai {$nilai}. Menunjukkan pemahaman yang sangat mendalam terhadap seluruh materi. Dedikasi dan ketekunan dalam belajar patut diacungi jempol.";
        } elseif ($nilai >= 80) {
            return "PRESTASI SANGAT BAIK! Siswa telah berhasil menyelesaikan kursus dengan nilai {$nilai}. Menunjukkan kemampuan analisis yang baik dan ketekunan dalam menyelesaikan pembelajaran.";
        } elseif ($nilai >= 70) {
            return "PRESTASI BAIK! Siswa telah menyelesaikan kursus dengan nilai {$nilai}. Menunjukkan komitmen dan ketekunan dalam proses belajar dari awal hingga akhir program.";
        } elseif ($nilai >= 60) {
            return "Siswa telah menyelesaikan kursus dengan nilai {$nilai}. Ketekunan dan konsistensi dalam belajar telah membuahkan hasil yang positif dalam pencapaian pembelajaran.";
        } else {
            return "Siswa telah menyelesaikan kursus dengan nilai {$nilai}. Program ini memberikan dasar-dasar pengetahuan yang diperlukan untuk pengembangan lebih lanjut.";
        }
    }
}