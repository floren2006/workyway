<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_kursus_detail($kursus_id) {
        $this->db->select('k.*, u.nama as nama_instruktur, u.foto_profil as foto_instruktur');
        $this->db->from('kursus k');
        $this->db->join('users u', 'k.instruktur_id = u.user_id', 'left');
        $this->db->where('k.kursus_id', $kursus_id);
        $query = $this->db->get();
        $kursus = $query->row();
        
        // Tambahkan default value jika properti tidak ada
        if ($kursus) {
            $kursus->jadwal_mulai = isset($kursus->jadwal_mulai) ? $kursus->jadwal_mulai : date('Y-m-d');
            $kursus->jadwal_selesai = isset($kursus->jadwal_selesai) ? $kursus->jadwal_selesai : date('Y-m-d', strtotime('+3 months'));
            
            // Jika tidak ada instruktur_id, cari dari guru freelance atau lpk
            if (empty($kursus->nama_instruktur)) {
                if ($kursus->guru_id) {
                    $this->db->select('u.nama, u.foto_profil');
                    $this->db->from('guru_freelance g');
                    $this->db->join('users u', 'g.user_id = u.user_id');
                    $this->db->where('g.guru_id', $kursus->guru_id);
                    $guru = $this->db->get()->row();
                    if ($guru) {
                        $kursus->nama_instruktur = $guru->nama;
                        $kursus->foto_instruktur = $guru->foto_profil;
                    }
                } elseif ($kursus->lpk_id) {
                    $this->db->select('nama_lembaga, user_id');
                    $this->db->from('lpk');
                    $this->db->where('lpk_id', $kursus->lpk_id);
                    $lpk = $this->db->get()->row();
                    if ($lpk) {
                        $kursus->nama_instruktur = $lpk->nama_lembaga;
                    }
                }
            }
        }
        
        return $kursus;
    }

    public function get_enrollment_status($user_id, $kursus_id) {
        // Cari siswa_id dari user_id
        $this->db->select('siswa_id');
        $this->db->from('siswa');
        $this->db->where('user_id', $user_id);
        $siswa = $this->db->get()->row();
        
        if (!$siswa) {
            return null;
        }
        
        // Cek dari tabel enrollment
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('siswa_id', $siswa->siswa_id);
        $query = $this->db->get('enrollment');
        
        return $query->row();
    }
    
    public function get_kursus_by_materi($materi_id) {
        $this->db->select('k.*');
        $this->db->from('kursus k');
        $this->db->join('materi_kursus m', 'k.kursus_id = m.kursus_id');
        $this->db->where('m.materi_id', $materi_id);
        return $this->db->get()->row();
    }

    public function get_materi_by_kursus($kursus_id) {
        $this->db->select('*');
        $this->db->from('materi_kursus');
        $this->db->where('kursus_id', $kursus_id);
        $this->db->order_by('materi_id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_materi_detail($materi_id) {
        return $this->db->get_where('materi_kursus', ['materi_id' => $materi_id])->row();
    }
    
    public function get_progress($siswa_id, $materi_id) {
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('materi_id', $materi_id);
        $result = $this->db->get('progress_materi')->row();
        
        if ($result) {
            return $result->status;
        }
        
        return 'incomplete';
    }
    
    // =============== FUNGSI UNTUK DASHBOARD ===============
    
    /**
     * Mendapatkan semua kursus yang diikuti siswa beserta progress
     */
    public function get_kursus_by_siswa($siswa_id) {
        $this->db->select('e.*, k.*');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->where('e.siswa_id', $siswa_id);
        $this->db->order_by('e.tanggal_daftar', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Mendapatkan progress kursus untuk siswa tertentu
     */
    public function get_progress_kursus($kursus_id, $siswa_id) {
        // Hitung total materi dalam kursus
        $this->db->where('kursus_id', $kursus_id);
        $total_materi = $this->db->count_all_results('materi_kursus');

        // Hitung materi yang sudah diselesaikan oleh siswa
        $this->db->select('COUNT(*) as completed');
        $this->db->from('progress_materi pm');
        $this->db->join('materi_kursus mk', 'pm.materi_id = mk.materi_id');
        $this->db->where('mk.kursus_id', $kursus_id);
        $this->db->where('pm.siswa_id', $siswa_id);
        $this->db->where('pm.status', 'completed');
        $completed_result = $this->db->get()->row();
        $completed_materi = $completed_result ? $completed_result->completed : 0;

        // Hitung persentase
        $progress = $total_materi > 0 ? round(($completed_materi / $total_materi) * 100) : 0;

        return array(
            'total_materi' => $total_materi,
            'completed_materi' => $completed_materi,
            'progress' => $progress
        );
    }

    /**
     * Mendapatkan materi berikutnya yang harus dipelajari
     */
    public function get_next_materi($kursus_id, $siswa_id) {
        // Ambil semua materi dalam kursus, urutkan
        $this->db->select('m.*');
        $this->db->from('materi_kursus m');
        $this->db->where('m.kursus_id', $kursus_id);
        $this->db->order_by('m.materi_id', 'ASC');
        $all_materi = $this->db->get()->result();

        // Untuk setiap materi, cek progress
        foreach ($all_materi as $materi) {
            $this->db->select('status');
            $this->db->from('progress_materi');
            $this->db->where('materi_id', $materi->materi_id);
            $this->db->where('siswa_id', $siswa_id);
            $progress = $this->db->get()->row();

            // Jika belum ada progress atau status bukan completed, maka ini materi berikutnya
            if (!$progress || $progress->status != 'completed') {
                return $materi;
            }
        }

        // Jika semua sudah selesai, kembalikan materi pertama (untuk review)
        if (!empty($all_materi)) {
            return $all_materi[0];
        }

        return null;
    }

    /**
     * Mendapatkan data rating dan review dari enrollment
     */
    public function get_rating_review($kursus_id, $siswa_id) {
        $this->db->select('rating, review');
        $this->db->from('enrollment');
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('siswa_id', $siswa_id);
        return $this->db->get()->row();
    }

    /**
     * Mendapatkan statistik kursus untuk dashboard
     */
    public function get_kursus_stats($siswa_id) {
        // Get all enrolled courses
        $enrollments = $this->get_kursus_by_siswa($siswa_id);
        
        $stats = [
            'total_kursus' => 0,
            'kursus_aktif' => 0,
            'kursus_selesai' => 0,
            'total_progress' => 0
        ];
        
        foreach($enrollments as $enrollment) {
            $progress = $this->get_progress_kursus($enrollment->kursus_id, $siswa_id);
            
            $stats['total_kursus']++;
            $stats['total_progress'] += $progress['progress'];
            
            if($progress['progress'] == 100) {
                $stats['kursus_selesai']++;
            } else {
                $stats['kursus_aktif']++;
            }
        }
        
        // Calculate average progress
        if($stats['total_kursus'] > 0) {
            $stats['rata_progress'] = round($stats['total_progress'] / $stats['total_kursus']);
        } else {
            $stats['rata_progress'] = 0;
        }
        
        return $stats;
    }

    public function get_enrollment_details($siswa_id)
    {
        $this->db->select('
            e.enrollment_id,
            e.kursus_id,
            e.tanggal_daftar,
            e.rating,
            e.review,
            e.nilai,
            e.sertifikat,

            k.judul_kursus,
            k.deskripsi,
            k.durasi,
            k.biaya,

            (SELECT nama FROM users WHERE user_id = (
                CASE 
                    WHEN k.guru_id IS NOT NULL THEN (SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id)
                    WHEN k.instruktur_id IS NOT NULL THEN (SELECT user_id FROM instruktur WHERE instruktur_id = k.instruktur_id)
                    ELSE NULL
                END
            )) AS instruktur,

            (SELECT COUNT(*) 
             FROM materi_kursus m 
             WHERE m.kursus_id = k.kursus_id) AS total_materi,

            (SELECT COUNT(*) 
             FROM progress_materi pm 
             WHERE pm.materi_id IN (
                 SELECT materi_id 
                 FROM materi_kursus 
                 WHERE kursus_id = k.kursus_id
             )
             AND pm.siswa_id = e.siswa_id
             AND pm.status = "completed") AS completed_materi
        ');

        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->where('e.siswa_id', $siswa_id);
        $this->db->order_by('e.tanggal_daftar', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * Cek apakah siswa sudah menyelesaikan kursus
     */
    public function is_kursus_completed($kursus_id, $siswa_id) {
        $progress = $this->get_progress_kursus($kursus_id, $siswa_id);
        return ($progress['progress'] == 100);
    }

    /**
     * Mendapatkan kursus yang sudah selesai tapi belum di-rating
     */
    public function get_completed_unrated_kursus($siswa_id) {
        $enrollments = $this->get_enrollment_details($siswa_id);
        $completed_unrated = [];
        
        foreach($enrollments as $enrollment) {
            $progress = ($enrollment->total_materi > 0) ? 
                       round(($enrollment->completed_materi / $enrollment->total_materi) * 100) : 0;
            
            if($progress == 100 && empty($enrollment->rating)) {
                $completed_unrated[] = $enrollment;
            }
        }
        
        return $completed_unrated;
    }

    /**
     * Update rating dan review
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
     * Mendapatkan enrollment by id dengan data lengkap
     */
    public function get_enrollment_by_id_with_details($enrollment_id)
    {
        $this->db->select('
            e.*,
            k.judul_kursus,
            k.deskripsi,
            (SELECT nama FROM users WHERE user_id = (
                CASE 
                    WHEN k.guru_id IS NOT NULL THEN (SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id)
                    WHEN k.instruktur_id IS NOT NULL THEN (SELECT user_id FROM instruktur WHERE instruktur_id = k.instruktur_id)
                    ELSE NULL
                END
            )) AS instruktur,
            s.nama AS nama_siswa
        ');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->join('siswa s', 's.siswa_id = e.siswa_id');
        $this->db->where('e.enrollment_id', $enrollment_id);

        $result = $this->db->get()->row();
        
        return $result;
    }

    
    // =============== FUNGSI DISKUSI ===============
    
    public function get_diskusi_by_materi($materi_id) {
        $this->db->select('f.*, u.nama as nama_lengkap, u.role as role_pengirim');
        $this->db->from('forum f');
        $this->db->join('users u', 'u.user_id = f.pengirim_id');
        $this->db->where('f.materi_id', $materi_id);
        $this->db->where('f.tipe', 'thread');
        $this->db->order_by('f.tanggal_post', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_balasan_by_thread($thread_id, $materi_id = null) {
        // Dapatkan thread utama
        $this->db->where('forum_id', $thread_id);
        $thread = $this->db->get('forum')->row();
        
        if (!$thread) {
            return array();
        }
        
        // Jika materi_id tidak diberikan, gunakan dari thread
        if ($materi_id === null) {
            $materi_id = $thread->materi_id;
        }
        
        // Ambil semua postingan di materi yang sama setelah thread ID
        $this->db->select('f.*, u.nama as nama_pengirim, u.role as role_pengirim');
        $this->db->from('forum f');
        $this->db->join('users u', 'u.user_id = f.pengirim_id');
        $this->db->where('f.materi_id', $materi_id);
        $this->db->where('f.forum_id >', $thread_id);
        $this->db->where('f.tipe', 'chat');
        $this->db->order_by('f.tanggal_post', 'ASC');
        
        $balasan = $this->db->get()->result();
        
        // Filter hanya balasan yang relevan
        $filtered_balasan = [];
        foreach ($balasan as $post) {
            if ($post->tipe == 'thread') {
                break; // Jika menemukan thread baru, berhenti
            }
            $filtered_balasan[] = $post;
        }
        
        return $filtered_balasan;
    }
    
    public function count_balasan_for_thread($thread_id, $materi_id = null) {
        // Dapatkan thread utama
        $this->db->where('forum_id', $thread_id);
        $thread = $this->db->get('forum')->row();
        
        if (!$thread) {
            return 0;
        }
        
        if ($materi_id === null) {
            $materi_id = $thread->materi_id;
        }
        
        // Hitung semua chat setelah thread ini pada materi yang sama
        $this->db->where('materi_id', $materi_id);
        $this->db->where('forum_id >', $thread_id);
        $this->db->where('tipe', 'chat');
        
        $all_chat = $this->db->get('forum')->result();
        
        // Filter manual untuk menghindari thread baru
        $count = 0;
        foreach ($all_chat as $post) {
            if ($post->tipe == 'thread') {
                break;
            }
            $count++;
        }
        
        return $count;
    }
    
    public function tambah_thread($data) {
        $data['tipe'] = 'thread';
        $data['tanggal_post'] = date('Y-m-d H:i:s');
        return $this->db->insert('forum', $data);
    }
    
    public function tambah_balasan($data) {
        $data['tipe'] = 'chat';
        $data['tanggal_post'] = date('Y-m-d H:i:s');
        return $this->db->insert('forum', $data);
    }
    
    // =============== FUNGSI TUGAS ===============
    
    public function get_tugas_by_materi($materi_id) {
        $this->db->select('*');
        $this->db->from('tugas');
        $this->db->where('materi_id', $materi_id);
        $this->db->order_by('deadline', 'ASC');
        return $this->db->get()->result();
    }

    public function get_tugas_by_id($tugas_id) {
        $this->db->select('*');
        $this->db->from('tugas');
        $this->db->where('tugas_id', $tugas_id);
        return $this->db->get()->row();
    }

    public function get_pengumpulan_tugas($siswa_id, $tugas_id) {
        $this->db->select('*');
        $this->db->from('pengumpulan_tugas');
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('tugas_id', $tugas_id);
        return $this->db->get()->row();
    }

    public function get_pengumpulan_by_id($pengumpulan_id) {
        $this->db->select('p.*, s.nama as nama_siswa, t.judul_tugas, t.materi_id');
        $this->db->from('pengumpulan_tugas p');
        $this->db->join('siswa s', 'p.siswa_id = s.siswa_id');
        $this->db->join('tugas t', 'p.tugas_id = t.tugas_id');
        $this->db->where('p.pengumpulan_id', $pengumpulan_id);
        return $this->db->get()->row();
    }

    public function insert_pengumpulan_tugas($data) {
        return $this->db->insert('pengumpulan_tugas', $data);
    }

    public function update_pengumpulan_tugas($pengumpulan_id, $data) {
        $this->db->where('pengumpulan_id', $pengumpulan_id);
        return $this->db->update('pengumpulan_tugas', $data);
    }

    public function hapus_pengumpulan($pengumpulan_id) {
        $this->db->where('pengumpulan_id', $pengumpulan_id);
        return $this->db->delete('pengumpulan_tugas');
    }
    
    public function get_all_pengumpulan($tugas_id) {
        $this->db->select('p.*, s.nama as nama_siswa');
        $this->db->from('pengumpulan_tugas p');
        $this->db->join('siswa s', 'p.siswa_id = s.siswa_id');
        $this->db->where('p.tugas_id', $tugas_id);
        $this->db->order_by('p.tanggal_kumpul', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_deadlines_terdekat($siswa_id, $limit = 5) {
        $this->db->select('t.*, m.judul_materi, p.status as status_pengumpulan');
        $this->db->from('tugas t');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->join('pengumpulan_tugas p', 't.tugas_id = p.tugas_id AND p.siswa_id = ' . $siswa_id, 'left');
        $this->db->where('t.deadline >', date('Y-m-d H:i:s'));
        $this->db->order_by('t.deadline', 'ASC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
    
    public function get_tugas_terlambat($siswa_id) {
        $this->db->select('t.*, m.judul_materi');
        $this->db->from('tugas t');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->join('enrollment e', 'm.kursus_id = e.kursus_id');
        $this->db->join('pengumpulan_tugas p', 't.tugas_id = p.tugas_id AND p.siswa_id = ' . $siswa_id, 'left');
        $this->db->where('e.siswa_id', $siswa_id);
        $this->db->where('t.deadline <', date('Y-m-d H:i:s'));
        $this->db->where('p.tugas_id IS NULL OR p.status !=', 'dikumpulkan');
        return $this->db->get()->result();
    }

    public function get_tugas_by_kursus($kursus_id) {
        $this->db->select('t.*, m.judul_materi, m.tipe_materi');
        $this->db->from('tugas t');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->where('m.kursus_id', $kursus_id);
        $this->db->order_by('t.deadline', 'ASC');
        return $this->db->get()->result();
    }

    public function get_tugas_with_status($siswa_id, $materi_id) {
        // Ambil semua tugas untuk materi ini saja
        $tugas_list = $this->get_tugas_by_materi($materi_id);
        
        if (empty($tugas_list)) {
            return array();
        }
        
        // Ambil data pengumpulan untuk setiap tugas
        foreach($tugas_list as $tugas) {
            $pengumpulan = $this->get_pengumpulan_tugas($siswa_id, $tugas->tugas_id);
            $tugas->pengumpulan = $pengumpulan;
            
            // Tentukan status
            $deadline = strtotime($tugas->deadline);
            $now = time();
            
            if ($pengumpulan) {
                if ($pengumpulan->nilai !== null) {
                    $tugas->status = 'dinilai';
                    $tugas->status_text = '✓ Dinilai';
                } else {
                    $tugas->status = 'dikumpulkan';
                    $tugas->status_text = '✓ Dikumpulkan';
                }
            } else {
                if ($now > $deadline) {
                    $tugas->status = 'terlambat';
                    $tugas->status_text = '⚠ Terlambat';
                } else {
                    $tugas->status = 'belum_dikumpulkan';
                    $tugas->status_text = '○ Belum';
                }
            }
            
            $tugas->is_late = ($now > $deadline && !$pengumpulan);
            $tugas->deadline_formatted = date('d M Y H:i', $deadline);
            $tugas->deadline_timestamp = $deadline;
        }
        
        return $tugas_list;
    }
    
    /**
     * Mendapatkan progress tugas untuk kursus tertentu
     */
    public function get_progress_tugas_kursus($kursus_id, $siswa_id) {
        $this->db->select('
            t.*,
            m.judul_materi,
            p.pengumpulan_id,
            p.status as status_pengumpulan,
            p.nilai,
            p.tanggal_kumpul,
            p.feedback
        ');
        $this->db->from('tugas t');
        $this->db->join('materi_kursus m', 't.materi_id = m.materi_id');
        $this->db->join('pengumpulan_tugas p', 't.tugas_id = p.tugas_id AND p.siswa_id = ' . $siswa_id, 'left');
        $this->db->where('m.kursus_id', $kursus_id);
        $this->db->order_by('t.deadline', 'ASC');
        
        return $this->db->get()->result();
    }

    // =============== FUNGSI TAMBAHAN UNTUK DASHBOARD ===============

    /**
     * Update progress materi
     */
    public function update_progress_materi($siswa_id, $materi_id) {
        // Cek apakah progress sudah ada
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('materi_id', $materi_id);
        $existing = $this->db->get('progress_materi')->row();
        
        if ($existing) {
            // Update status menjadi completed
            $this->db->where('progress_id', $existing->progress_id);
            return $this->db->update('progress_materi', [
                'status' => 'completed',
                'tanggal_update' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Insert baru
            return $this->db->insert('progress_materi', [
                'siswa_id' => $siswa_id,
                'materi_id' => $materi_id,
                'status' => 'completed',
                'tanggal_update' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Cek apakah pengumpulan sudah ada
     */
    public function is_pengumpulan_exists($siswa_id, $tugas_id) {
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('tugas_id', $tugas_id);
        return $this->db->count_all_results('pengumpulan_tugas') > 0;
    }

    /**
     * Mendapatkan data progress untuk dashboard
     */
    public function get_dashboard_progress($siswa_id) {
        // Hitung total progress semua kursus
        $this->db->select('k.kursus_id, k.judul_kursus');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->where('e.siswa_id', $siswa_id);
        $kursus_list = $this->db->get()->result();
        
        $total_progress = 0;
        $total_kursus = count($kursus_list);
        
        foreach ($kursus_list as $kursus) {
            $progress_data = $this->get_progress_kursus($kursus->kursus_id, $siswa_id);
            $total_progress += $progress_data['progress'];
        }
        
        $average_progress = $total_kursus > 0 ? round($total_progress / $total_kursus) : 0;
        
        return [
            'total_kursus' => $total_kursus,
            'average_progress' => $average_progress
        ];
    }

    /**
     * Mendapatkan jumlah sertifikat
     */
    public function get_jumlah_sertifikat($siswa_id) {
        $this->db->where('siswa_id', $siswa_id);
        $this->db->where('sertifikat IS NOT NULL');
        $this->db->where('sertifikat !=', '');
        return $this->db->count_all_results('enrollment');
    }
}