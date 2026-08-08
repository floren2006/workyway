<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // =============== FUNGSI DASAR ===============
   
    public function get_kursus_by_guru($guru_id) {
        $this->db->select('k.*, kk.nama_kategori');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where_in('k.status_kursus', ['active', 'published']);
        $this->db->order_by('k.judul_kursus', 'ASC');
        return $this->db->get()->result();
    }
   
    public function get_kursus_detail($kursus_id) {
        $this->db->select('k.*, u.nama as nama_guru, u.foto_profil as foto_guru');
        $this->db->from('kursus k');
        $this->db->join('guru_freelance g', 'k.guru_id = g.guru_id', 'left');
        $this->db->join('users u', 'g.user_id = u.user_id', 'left');
        $this->db->where('k.kursus_id', $kursus_id);
        return $this->db->get()->row();
    }
   
    public function is_kursus_milik_guru($kursus_id, $guru_id) {
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('guru_id', $guru_id);
        return $this->db->get('kursus')->num_rows() > 0;
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
   
    public function get_siswa_by_kursus($kursus_id) {
        $this->db->select('s.*, u.nama, u.email, u.foto_profil, u.user_id');
        $this->db->from('enrollment e');
        $this->db->join('siswa s', 'e.siswa_id = s.siswa_id');
        $this->db->join('users u', 's.user_id = u.user_id');
        $this->db->where('e.kursus_id', $kursus_id);
        $this->db->group_by('s.siswa_id');
        return $this->db->get()->result();
    }

    // =============== FUNGSI DISKUSI DENGAN DATA PENGIRIM ===============
   
    public function get_forum_messages_with_sender($kursus_id, $materi_id) {
        $this->db->select('f.*, u.nama as pengirim_nama, u.role as pengirim_role, u.foto_profil as pengirim_foto');
        $this->db->from('forum f');
        $this->db->join('users u', 'u.user_id = f.pengirim_id');
        $this->db->where('f.kursus_id', $kursus_id);
        $this->db->where('f.materi_id', $materi_id);
        $this->db->order_by('f.tanggal_post', 'DESC');
        $result = $this->db->get()->result_array();
       
        return $result ?: [];
    }
   
    public function get_all_forum_messages_by_course_with_sender($kursus_id) {
        $this->db->select('f.*, u.nama as pengirim_nama, u.role as pengirim_role, u.foto_profil as pengirim_foto');
        $this->db->from('forum f');
        $this->db->join('users u', 'u.user_id = f.pengirim_id');
        $this->db->where('f.kursus_id', $kursus_id);
        $this->db->order_by('f.tanggal_post', 'DESC');
        $result = $this->db->get()->result_array();
       
        return $result ?: [];
    }
   
    public function get_replies_for_thread_with_sender($thread_id, $materi_id = null) {
        // Ambil thread terlebih dahulu untuk mendapatkan kursus_id dan materi_id jika tidak diberikan
        $thread = $this->get_message_by_id($thread_id);
       
        if (!$thread) {
            return [];
        }
       
        // Jika materi_id tidak diberikan, gunakan dari thread
        if (!$materi_id) {
            $materi_id = $thread['materi_id'];
        }
       
        $this->db->select('f.*, u.nama as pengirim_nama, u.role as pengirim_role, u.foto_profil as pengirim_foto');
        $this->db->from('forum f');
        $this->db->join('users u', 'u.user_id = f.pengirim_id');
        $this->db->where('f.kursus_id', $thread['kursus_id']);
        $this->db->where('f.materi_id', $materi_id);
        $this->db->where('f.tipe', 'chat');
        $this->db->where('f.forum_id >', $thread_id);
        $this->db->order_by('f.forum_id', 'ASC');
        $result = $this->db->get()->result_array();
       
        return $result ?: [];
    }
   
    public function get_thread_by_id($thread_id) {
        $result = $this->db->get_where('forum', ['forum_id' => $thread_id])->row_array();
        return $result ?: [];
    }
   
    public function get_pesan_by_id($forum_id) {
        $result = $this->db->get_where('forum', ['forum_id' => $forum_id])->row_array();
        return $result ?: [];
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
   
    public function hapus_pesan($forum_id) {
        $this->db->where('forum_id', $forum_id);
        return $this->db->delete('forum');
    }

    // =============== FUNGSI NOTIFIKASI ===============
   
    public function create_notifications_for_students($kursus_id, $guru_nama, $pesan, $forum_id, $guru_user_id) {
        // Ambil semua siswa yang mengikuti kursus ini
        $siswa_list = $this->get_siswa_by_kursus($kursus_id);
       
        if (empty($siswa_list)) {
            return false;
        }
       
        // Buat notifikasi untuk setiap siswa
        foreach ($siswa_list as $siswa) {
            $notif_data = [
                'penerima_id' => $siswa->user_id,
                'judul' => 'Diskusi Baru dari Guru',
                'isi' => $guru_nama . ' memulai diskusi baru: "' . substr($pesan, 0, 100) . '..."',
                'tanggal' => date('Y-m-d H:i:s'),
                'status' => 'unread'
            ];
           
            $this->db->insert('notifikasi', $notif_data);
        }
       
        return true;
    }
   
    public function create_notification_for_user($user_id, $judul, $isi, $related_id, $sender_id) {
        $notif_data = [
            'penerima_id' => $user_id,
            'judul' => $judul,
            'isi' => $isi,
            'tanggal' => date('Y-m-d H:i:s'),
            'status' => 'unread'
        ];
       
        return $this->db->insert('notifikasi', $notif_data);
    }

    // =============== FUNGSI ALIAS UNTUK COMPATIBILITY ===============
   
    public function get_kursus_by_guru_for_forum($guru_id) {
        $result = $this->get_kursus_by_guru($guru_id);
        return $result ? array_map(function($item) {
            return (array) $item;
        }, $result) : [];
    }
   
    public function get_course_details_for_forum($kursus_id) {
        $result = $this->get_kursus_detail($kursus_id);
        return $result ? (array) $result : [];
    }
   
    public function get_materi_by_kursus_for_forum($kursus_id) {
        $result = $this->get_materi_by_kursus($kursus_id);
        return $result ? array_map(function($item) {
            return (array) $item;
        }, $result) : [];
    }
   
    public function get_forum_messages($kursus_id, $materi_id = null) {
        if ($materi_id && $materi_id != 'all') {
            return $this->get_forum_messages_with_sender($kursus_id, $materi_id);
        } else {
            return $this->get_all_forum_messages_by_course_with_sender($kursus_id);
        }
    }
   
    public function get_message_by_id($forum_id) {
        return $this->get_pesan_by_id($forum_id);
    }
   
    public function send_forum_message($data) {
        if (isset($data['tipe']) && $data['tipe'] == 'thread') {
            return $this->tambah_thread($data);
        } else {
            return $this->tambah_balasan($data);
        }
    }
   
    public function delete_forum_message($forum_id, $pengirim_id) {
        // Validasi bahwa pengirim adalah yang menghapus
        $pesan = $this->get_pesan_by_id($forum_id);
        if ($pesan && $pesan['pengirim_id'] == $pengirim_id) {
            return $this->hapus_pesan($forum_id);
        }
        return false;
    }
   
    public function count_messages_by_course($kursus_id) {
        $this->db->where('kursus_id', $kursus_id);
        return $this->db->count_all_results('forum');
    }
   
    public function is_course_owned_by_guru($kursus_id, $guru_id) {
        return $this->is_kursus_milik_guru($kursus_id, $guru_id);
    }
   
    public function get_messages_for_student_view($kursus_id, $materi_id) {
        return $this->get_forum_messages_with_sender($kursus_id, $materi_id);
    }
   
    public function get_replies_for_thread($thread_id) {
        return $this->get_replies_for_thread_with_sender($thread_id);
    }
   
    public function count_replies_for_thread($thread_id) {
        return count($this->get_replies_for_thread_with_sender($thread_id));
    }
   
    public function get_all_forum_messages_by_course($kursus_id) {
        return $this->get_all_forum_messages_by_course_with_sender($kursus_id);
    }
   
    public function get_new_messages($kursus_id, $materi_id = null, $last_message_id = 0) {
        if ($materi_id && $materi_id != 'all') {
            $this->db->select('f.*, u.nama as pengirim_nama, u.role as pengirim_role, u.foto_profil as pengirim_foto');
            $this->db->from('forum f');
            $this->db->join('users u', 'u.user_id = f.pengirim_id');
            $this->db->where('f.kursus_id', $kursus_id);
            $this->db->where('f.materi_id', $materi_id);
            $this->db->where('f.forum_id >', $last_message_id);
            $this->db->order_by('f.forum_id', 'ASC');
            $result = $this->db->get()->result_array();
        } else {
            $this->db->select('f.*, u.nama as pengirim_nama, u.role as pengirim_role, u.foto_profil as pengirim_foto');
            $this->db->from('forum f');
            $this->db->join('users u', 'u.user_id = f.pengirim_id');
            $this->db->where('f.kursus_id', $kursus_id);
            $this->db->where('f.forum_id >', $last_message_id);
            $this->db->order_by('f.forum_id', 'ASC');
            $result = $this->db->get()->result_array();
        }
       
        return $result ?: [];
    }
}
