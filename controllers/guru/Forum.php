<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum extends CI_Controller {

    private $guru_id;
    private $guru_data;

    public function __construct() {
        parent::__construct();
       
        $this->load->helper(['url', 'form']);
        $this->load->model('guru/Forum_model');
        $this->load->library('session');
        $this->load->database();

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Cek role
        if ($this->session->userdata('role') !== 'guru') {
            show_error('Akses ditolak');
        }

        // Ambil guru_id dari session
        $this->guru_id = $this->session->userdata('guru_id');
       
        if (!$this->guru_id) {
            show_error('Guru ID tidak ditemukan');
        }
       
        // Ambil data guru
        $this->guru_data = $this->get_guru_data($this->guru_id);
    }

    /**
     * Ambil data guru dari database
     */
    private function get_guru_data($guru_id) {
        $this->db->select('g.*, u.user_id, u.nama, u.email, u.foto_profil');
        $this->db->from('guru_freelance g');
        $this->db->join('users u', 'g.user_id = u.user_id');
        $this->db->where('g.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
       
        // Jika tidak ditemukan, berikan data default
        if (empty($result)) {
            $result = [
                'guru_id' => $guru_id,
                'user_id' => 0,
                'nama' => 'Guru',
                'email' => 'guru@email.com',
                'foto_profil' => 'default.jpg'
            ];
        }
       
        return $result;
    }

    public function index() {
        redirect('guru/forum/forum');
    }

    public function forum() {
        $data = [
            'title' => 'Forum Diskusi',
            'guru' => $this->guru_data,
            'guru_id' => $this->guru_id
        ];
       
        // Ambil parameter dari URL
        $kursus_id = $this->input->get('kursus_id');
        $materi_id = $this->input->get('materi_id');
       
        // Ambil data kursus guru
        $kursus_list = $this->Forum_model->get_kursus_by_guru_for_forum($this->guru_id);
        $data['kursus_list'] = $this->convert_objects_to_array($kursus_list);
       
        // Jika ada kursus yang dipilih
        if ($kursus_id) {
            // Cek apakah kursus ini milik guru yang login
            if (!$this->Forum_model->is_course_owned_by_guru($kursus_id, $this->guru_id)) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kursus ini');
                redirect('guru/forum/forum');
            }
           
            $data['selected_kursus_id'] = $kursus_id;
           
            // Ambil detail kursus
            $selected_kursus = $this->Forum_model->get_course_details_for_forum($kursus_id);
            $data['selected_kursus'] = $selected_kursus ? (array)$selected_kursus : [];
           
            // Ambil materi
            $materi_list = $this->Forum_model->get_materi_by_kursus_for_forum($kursus_id);
            $data['materi_list'] = $this->convert_objects_to_array($materi_list);
           
            // Jika tidak ada materi_id dan ada materi_list, pilih materi pertama
            if (empty($materi_id) && !empty($data['materi_list'])) {
                $materi_id = $data['materi_list'][0]['materi_id'];
                redirect('guru/forum/forum?kursus_id=' . $kursus_id . '&materi_id=' . $materi_id);
                return;
            }
           
            // Ambil pesan dengan data pengirim yang lengkap
            if ($materi_id && $materi_id != 'all') {
                $messages = $this->Forum_model->get_forum_messages_with_sender($kursus_id, $materi_id);
            } else {
                // Jika 'all', ambil semua pesan dari semua materi kursus ini
                $messages = $this->Forum_model->get_all_forum_messages_by_course_with_sender($kursus_id);
            }
           
            // PROSES PESAN: Gabungkan thread dan balasan
            $organized_messages = [];
            foreach ($messages as $message) {
                if ($message['tipe'] == 'thread') {
                    // Cari balasan untuk thread ini
                    $replies = $this->Forum_model->get_replies_for_thread_with_sender($message['forum_id'], $message['materi_id']);
                    $message['balasan'] = $replies;
                    $message['jumlah_balasan'] = count($replies);
                    $organized_messages[] = $message;
                }
            }
           
            $data['messages'] = $organized_messages;
            $data['total_messages'] = count($messages);
           
            // Ambil daftar siswa yang mengikuti kursus ini
            $siswa_kursus = $this->Forum_model->get_siswa_by_kursus($kursus_id);
            $data['siswa_kursus'] = $this->convert_objects_to_array($siswa_kursus);
        } else {
            $data['selected_kursus_id'] = null;
            $data['selected_kursus'] = null;
            $data['materi_list'] = [];
            $data['messages'] = [];
            $data['total_messages'] = 0;
            $data['siswa_kursus'] = [];
        }
       
        $data['selected_materi_id'] = $materi_id;
        $data['current_user_id'] = $this->guru_data['user_id'] ?? 0;
       
        // Tampilkan pesan dari flashdata jika ada
        $data['message_success'] = $this->session->flashdata('success');
        $data['message_error'] = $this->session->flashdata('error');
       
        // Load view
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/forum', $data);
        $this->load->view('templates/guru_footer');
    }

    public function kirim_pesan() {
        // Validasi session
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $user_id = $this->guru_data['user_id'] ?? 0;
        $kursus_id = $this->input->post('kursus_id');
        $materi_id = $this->input->post('materi_id');
        $pesan = trim($this->input->post('pesan'));
       
        // Validasi
        if (empty($pesan) || empty($kursus_id)) {
            $this->session->set_flashdata('error', 'Pesan dan kursus harus diisi');
            redirect('guru/forum/forum?kursus_id=' . $kursus_id);
        }
       
        // Cek apakah kursus ini milik guru yang login
        if (!$this->Forum_model->is_course_owned_by_guru($kursus_id, $this->guru_id)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kursus ini');
            redirect('guru/forum/forum');
        }
       
        // Jika materi_id adalah 'all', pilih materi pertama dari kursus
        if ($materi_id == 'all') {
            $materi_list = $this->Forum_model->get_materi_by_kursus_for_forum($kursus_id);
            if (!empty($materi_list)) {
                $materi_id = $materi_list[0]['materi_id'];
            } else {
                $this->session->set_flashdata('error', 'Tidak ada materi untuk kursus ini');
                redirect('guru/forum/forum?kursus_id=' . $kursus_id);
            }
        }
       
        // Data untuk disimpan
        $forum_data = [
            'kursus_id' => $kursus_id,
            'materi_id' => $materi_id,
            'pengirim_id' => $user_id,
            'pesan' => htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'),
            'tipe' => 'thread',
            'tanggal_post' => date('Y-m-d H:i:s')
        ];
       
        // Simpan pesan ke forum
        $result = $this->Forum_model->send_forum_message($forum_data);
       
        if ($result) {
            // Ambil forum_id yang baru saja diinsert
            $forum_id = $this->db->insert_id();
           
            // Buat notifikasi untuk semua siswa yang mengikuti kursus ini
            $this->Forum_model->create_notifications_for_students(
                $kursus_id,
                $this->guru_data['nama'],
                $pesan,
                $forum_id,
                $user_id
            );
           
            $this->session->set_flashdata('success', 'Pesan berhasil dikirim');
        } else {
            $error = $this->db->error();
            $this->session->set_flashdata('error', 'Gagal mengirim pesan. Silakan coba lagi.');
           
            // Log error
            log_message('error', 'Forum Guru - Gagal kirim pesan: ' . json_encode($error));
        }
       
        $redirect_url = 'guru/forum/forum?kursus_id=' . $kursus_id;
        if ($materi_id && $materi_id != 'all') {
            $redirect_url .= '&materi_id=' . $materi_id;
        }
       
        redirect($redirect_url);
    }

    public function kirim_balasan() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $user_id = $this->guru_data['user_id'] ?? 0;
        $forum_id = $this->input->post('thread_id');
        $materi_id = $this->input->post('materi_id');
        $balasan = trim($this->input->post('balasan'));
       
        // Validasi
        if (empty($balasan) || empty($forum_id)) {
            $this->session->set_flashdata('error', 'Balasan tidak boleh kosong');
            redirect('guru/forum/forum');
        }
       
        // Cari thread untuk mendapatkan kursus_id
        $thread = $this->Forum_model->get_message_by_id($forum_id);
        if (!$thread) {
            $this->session->set_flashdata('error', 'Thread tidak ditemukan');
            redirect('guru/forum/forum');
        }
       
        // Cek apakah kursus ini milik guru yang login
        if (!$this->Forum_model->is_course_owned_by_guru($thread['kursus_id'], $this->guru_id)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kursus ini');
            redirect('guru/forum/forum');
        }
       
        $data = [
            'kursus_id' => $thread['kursus_id'],
            'materi_id' => $materi_id,
            'pengirim_id' => $user_id,
            'pesan' => htmlspecialchars($balasan, ENT_QUOTES, 'UTF-8'),
            'tipe' => 'chat',
            'tanggal_post' => date('Y-m-d H:i:s')
        ];
       
        $result = $this->Forum_model->tambah_balasan($data);
       
        if ($result) {
            $this->session->set_flashdata('success', 'Balasan berhasil dikirim');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengirim balasan');
        }
       
        redirect('guru/forum/forum?kursus_id=' . $thread['kursus_id'] . '&materi_id=' . $materi_id);
    }

    public function hapus_pesan($forum_id) {
        // Cek apakah user adalah pengirim pesan
        $message = $this->Forum_model->get_message_by_id($forum_id);
       
        if (!$message || $message['pengirim_id'] != $this->guru_data['user_id']) {
            $this->session->set_flashdata('error', 'Pesan tidak ditemukan atau tidak memiliki akses');
            redirect('guru/forum/forum');
        }
       
        // Cek apakah kursus ini milik guru yang login
        if (!$this->Forum_model->is_course_owned_by_guru($message['kursus_id'], $this->guru_id)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kursus ini');
            redirect('guru/forum/forum');
        }
       
        $result = $this->Forum_model->delete_forum_message($forum_id, $this->guru_data['user_id']);
       
        if ($result) {
            $this->session->set_flashdata('success', 'Pesan berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pesan');
        }
       
        redirect('guru/forum/forum?kursus_id=' . $message['kursus_id']);
    }
   
    /**
     * Helper function untuk mengkonversi objek ke array
     */
    private function convert_objects_to_array($data) {
        if (is_object($data)) {
            // Jika single object
            return (array)$data;
        } elseif (is_array($data)) {
            // Jika array of objects
            $result = [];
            foreach ($data as $key => $value) {
                if (is_object($value)) {
                    $result[$key] = (array)$value;
                } else {
                    $result[$key] = $value;
                }
            }
            return $result;
        }
        return $data;
    }
}