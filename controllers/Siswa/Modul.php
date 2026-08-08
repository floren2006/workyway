<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('siswa/Modul_model');
        $this->load->model('siswa/Dashboard_model');
        $this->load->library('session');
        $this->load->library('upload');
        
        $this->user_id = $this->session->userdata('user_id');

        if (!$this->user_id) {
            redirect('login');
        }
    }

    public function index($kursus_id = null)
    {
        if (!$kursus_id) {
            redirect('siswa/dashboard');
        }

        // ================== DATA DASAR ==================
        $data['kursus'] = $this->Modul_model->get_kursus_detail($kursus_id);
        $data['materi_list'] = $this->Modul_model->get_materi_by_kursus($kursus_id);
        $data['active_tab'] = $this->input->get('tab') ?: 'materi';

        // ================== MATERI AKTIF ==================
        $materi_aktif_id = $this->input->get('materi_id');
        if ($materi_aktif_id) {
            foreach ($data['materi_list'] as $materi) {
                if ($materi->materi_id == $materi_aktif_id) {
                    $data['materi_aktif'] = $materi;
                    break;
                }
            }
        }

        if (!isset($data['materi_aktif']) && !empty($data['materi_list'])) {
            $data['materi_aktif'] = $data['materi_list'][0];
        }

        // ================== SISWA ==================
        $data['siswa'] = $this->Dashboard_model->get_siswa_data($this->user_id);

        // ================== TUGAS ==================
        $data['tugas_list'] = [];
        $data['pengumpulan_tugas'] = [];

        if (!empty($data['siswa']) && isset($data['materi_aktif'])) {
            $data['tugas_list'] = $this->Modul_model->get_tugas_with_status(
                $data['siswa']->siswa_id,
                $data['materi_aktif']->materi_id
            );

            foreach ($data['tugas_list'] as $tugas) {
                $data['pengumpulan_tugas'][$tugas->tugas_id] =
                    $this->Modul_model->get_pengumpulan_tugas(
                        $data['siswa']->siswa_id,
                        $tugas->tugas_id
                    );
            }
        }

        // ================== DISKUSI ==================
        $data['diskusi_list'] = [];
        if (isset($data['materi_aktif'])) {
            $data['diskusi_list'] = $this->Modul_model->get_diskusi_by_materi(
                $data['materi_aktif']->materi_id
            );

            foreach ($data['diskusi_list'] as $diskusi) {
                $diskusi->jumlah_balasan =
                    $this->Modul_model->count_balasan_for_thread($diskusi->forum_id);
                $diskusi->balasan =
                    $this->Modul_model->get_balasan_by_thread($diskusi->forum_id);
            }
        }

        // ================== ENROLLMENT & PROGRESS ==================
        $data['enrollment'] = $this->Modul_model->get_enrollment_status(
            $this->user_id,
            $kursus_id
        );

        if ($data['enrollment'] && !empty($data['siswa']) && isset($data['materi_aktif'])) {
            $data['materi_progress'] = $this->Modul_model->get_progress(
                $data['siswa']->siswa_id,
                $data['materi_aktif']->materi_id
            );
        }

        // ================== VIEW ==================
        $this->load->view('siswa/modul/index', $data);
    }

    public function materi($materi_id = null)
    {
        if (!$materi_id) {
            redirect('siswa/dashboard');
        }

        // ================== DATA DASAR ==================
        $data['materi'] = $this->Modul_model->get_materi_detail($materi_id);
        $data['kursus'] = $this->Modul_model->get_kursus_by_materi($materi_id);
        
        if (!$data['kursus'] || !$data['materi']) {
            show_error('Materi atau kursus tidak ditemukan', 404);
        }
        
        $data['materi_list'] = $this->Modul_model->get_materi_by_kursus(
            $data['kursus']->kursus_id
        );

        $data['materi_aktif'] = $data['materi'];
        $data['active_tab'] = $this->input->get('tab') ?: 'materi';

        // ================== SISWA ==================
        $data['siswa'] = $this->Dashboard_model->get_siswa_data($this->user_id);

        // ================== TUGAS ==================
        $data['tugas_list'] = [];
        $data['pengumpulan_tugas'] = [];

        if (!empty($data['siswa'])) {
            $data['tugas_list'] = $this->Modul_model->get_tugas_with_status(
                $data['siswa']->siswa_id,
                $materi_id
            );

            foreach ($data['tugas_list'] as $tugas) {
                $data['pengumpulan_tugas'][$tugas->tugas_id] =
                    $this->Modul_model->get_pengumpulan_tugas(
                        $data['siswa']->siswa_id,
                        $tugas->tugas_id
                    );
            }
        }

        // ================== DISKUSI ==================
        $data['diskusi_list'] = $this->Modul_model->get_diskusi_by_materi($materi_id);

        foreach ($data['diskusi_list'] as $diskusi) {
            $diskusi->jumlah_balasan =
                $this->Modul_model->count_balasan_for_thread($diskusi->forum_id);
            $diskusi->balasan =
                $this->Modul_model->get_balasan_by_thread($diskusi->forum_id);
        }

        // ================== PROGRESS ==================
        if (!empty($data['siswa'])) {
            $data['materi_progress'] = $this->Modul_model->get_progress(
                $data['siswa']->siswa_id,
                $materi_id
            );
        }

        // ================== VIEW ==================
        $this->load->view('siswa/modul/index', $data);
    }

    // =============== FUNGSI DISKUSI ===============
    
    public function tambah_diskusi() {
        if ($this->input->post()) {
            $kursus_id = $this->input->post('kursus_id');
            $materi_id = $this->input->post('materi_id');
            $pesan = trim($this->input->post('pesan'));
            
            if (empty($pesan)) {
                $this->session->set_flashdata('error', 'Pesan tidak boleh kosong');
                redirect('siswa/modul/materi/' . $materi_id . '?tab=diskusi');
            }
            
            $data = array(
                'kursus_id' => $kursus_id,
                'materi_id' => $materi_id,
                'pengirim_id' => $this->user_id,
                'pesan' => $pesan,
                'tipe' => 'thread',
                'tanggal_post' => date('Y-m-d H:i:s')
            );
            
            // Simpan ke database
            $result = $this->db->insert('forum', $data);
            
            if ($result) {
                // Kirim notifikasi ke guru
                $this->kirim_notifikasi_ke_guru_thread($data);
                
                $this->session->set_flashdata('success', 'Diskusi berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan diskusi');
            }
            
            redirect('siswa/modul/materi/' . $materi_id . '?tab=diskusi');
        }
    }
    
    public function tambah_balasan() {
        if ($this->input->post()) {
            $thread_id = $this->input->post('thread_id');
            $materi_id = $this->input->post('materi_id');
            $balasan = trim($this->input->post('balasan'));
            
            if (empty($balasan)) {
                $this->session->set_flashdata('error', 'Balasan tidak boleh kosong');
                redirect('siswa/modul/materi/' . $materi_id . '?tab=diskusi');
            }
            
            // Ambil data thread utama
            $this->db->where('forum_id', $thread_id);
            $thread = $this->db->get('forum')->row();
            
            if (!$thread) {
                $this->session->set_flashdata('error', 'Thread tidak ditemukan');
                redirect('siswa/modul/materi/' . $materi_id . '?tab=diskusi');
            }
            
            // Simpan balasan ke database
            $data = array(
                'kursus_id' => $thread->kursus_id,
                'materi_id' => $materi_id,
                'pengirim_id' => $this->user_id,
                'pesan' => $balasan,
                'tipe' => 'chat',
                'tanggal_post' => date('Y-m-d H:i:s')
            );
            
            // Simpan ke database
            $result = $this->db->insert('forum', $data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Balasan berhasil ditambahkan');
                
                // Kirim notifikasi ke guru dan pengirim thread
                $this->kirim_notifikasi_ke_guru_balasan($thread, $balasan);
                
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan balasan');
            }
            
            redirect('siswa/modul/materi/' . $materi_id . '?tab=diskusi');
        }
    }

    private function kirim_notifikasi_ke_guru_thread($thread_data) {
        // Dapatkan data kursus
        $this->db->where('kursus_id', $thread_data['kursus_id']);
        $kursus = $this->db->get('kursus')->row();
        
        if (!$kursus) {
            return;
        }
        
        // Dapatkan data pengirim
        $this->db->where('user_id', $this->user_id);
        $pengirim = $this->db->get('users')->row();
        
        // Cari guru/instruktur yang terkait
        $guru_user_id = null;
        
        // Cek jika kursus memiliki guru freelance
        if ($kursus->guru_id) {
            $this->db->where('guru_id', $kursus->guru_id);
            $guru = $this->db->get('guru_freelance')->row();
            
            if ($guru) {
                $guru_user_id = $guru->user_id;
            }
        }
        // Cek jika kursus memiliki instruktur dari LPK
        elseif ($kursus->instruktur_id) {
            $this->db->where('instruktur_id', $kursus->instruktur_id);
            $instruktur = $this->db->get('instruktur')->row();
            
            if ($instruktur) {
                $guru_user_id = $instruktur->user_id;
            }
        }
        // Cek jika kursus dari LPK
        elseif ($kursus->lpk_id) {
            $this->db->where('lpk_id', $kursus->lpk_id);
            $lpk = $this->db->get('lpk')->row();
            
            if ($lpk) {
                $guru_user_id = $lpk->user_id;
            }
        }
        
        // Kirim notifikasi ke guru (jika ada dan bukan diri sendiri)
        if ($guru_user_id && $guru_user_id != $this->user_id) {
            $this->db->insert('notifikasi', [
                'penerima_id' => $guru_user_id,
                'judul' => 'Diskusi Baru',
                'isi' => ($pengirim ? $pengirim->nama : 'Seseorang') . 
                       ' memulai diskusi baru di kursus: ' . $kursus->judul_kursus,
                'tanggal' => date('Y-m-d H:i:s'),
                'status' => 'unread'
            ]);
        }
    }

    private function kirim_notifikasi_ke_guru_balasan($thread, $balasan_text) {
        // Dapatkan data kursus
        $this->db->where('kursus_id', $thread->kursus_id);
        $kursus = $this->db->get('kursus')->row();
        
        if (!$kursus) {
            return;
        }
        
        // Dapatkan data pengirim balasan
        $this->db->where('user_id', $this->user_id);
        $pengirim_balasan = $this->db->get('users')->row();
        
        // Dapatkan data pengirim thread
        $this->db->where('user_id', $thread->pengirim_id);
        $pengirim_thread = $this->db->get('users')->row();
        
        // Cari guru/instruktur yang terkait
        $guru_user_id = null;
        
        // Cek jika kursus memiliki guru freelance
        if ($kursus->guru_id) {
            $this->db->where('guru_id', $kursus->guru_id);
            $guru = $this->db->get('guru_freelance')->row();
            
            if ($guru) {
                $guru_user_id = $guru->user_id;
            }
        }
        // Cek jika kursus memiliki instruktur dari LPK
        elseif ($kursus->instruktur_id) {
            $this->db->where('instruktur_id', $kursus->instruktur_id);
            $instruktur = $this->db->get('instruktur')->row();
            
            if ($instruktur) {
                $guru_user_id = $instruktur->user_id;
            }
        }
        // Cek jika kursus dari LPK
        elseif ($kursus->lpk_id) {
            $this->db->where('lpk_id', $kursus->lpk_id);
            $lpk = $this->db->get('lpk')->row();
            
            if ($lpk) {
                $guru_user_id = $lpk->user_id;
            }
        }
        
        // Kirim notifikasi ke guru (jika ada dan bukan diri sendiri)
        if ($guru_user_id && $guru_user_id != $this->user_id) {
            $this->db->insert('notifikasi', [
                'penerima_id' => $guru_user_id,
                'judul' => 'Balasan Diskusi',
                'isi' => ($pengirim_balasan ? $pengirim_balasan->nama : 'Seseorang') . 
                       ' membalas diskusi di kursus: ' . $kursus->judul_kursus,
                'tanggal' => date('Y-m-d H:i:s'),
                'status' => 'unread'
            ]);
        }
        
        // Kirim notifikasi ke pengirim thread (jika bukan diri sendiri)
        if ($thread->pengirim_id != $this->user_id && $thread->pengirim_id != $guru_user_id) {
            $this->db->insert('notifikasi', [
                'penerima_id' => $thread->pengirim_id,
                'judul' => 'Balasan untuk Diskusi Anda',
                'isi' => ($pengirim_balasan ? $pengirim_balasan->nama : 'Seseorang') . 
                       ' membalas diskusi yang Anda buat di kursus: ' . $kursus->judul_kursus,
                'tanggal' => date('Y-m-d H:i:s'),
                'status' => 'unread'
            ]);
        }
    }

    // =============== FUNGSI TUGAS ===============

    public function tugas($materi_id = null) {
        if (!$materi_id) {
            redirect('siswa/dashboard');
        }

        // Ambil data materi dan kursus
        $data['materi'] = $this->Modul_model->get_materi_detail($materi_id);
        if (!$data['materi']) {
            show_error('Materi tidak ditemukan', 404);
        }
        
        $data['kursus'] = $this->Modul_model->get_kursus_by_materi($materi_id);
        $data['materi_list'] = $this->Modul_model->get_materi_by_kursus($data['kursus']->kursus_id);
        $data['siswa'] = $this->Dashboard_model->get_siswa_data($this->user_id);

        // Set materi aktif
        $data['materi_aktif'] = $data['materi'];
        $data['active_tab'] = 'tugas';

        // Ambil data tugas untuk materi ini saja
        if (!empty($data['siswa'])) {
            $data['tugas_list'] = $this->Modul_model->get_tugas_with_status(
                $data['siswa']->siswa_id,
                $materi_id
            );

            // Ambil data pengumpulan tugas untuk setiap tugas
            $data['pengumpulan_tugas'] = array();
            if (!empty($data['tugas_list'])) {
                foreach ($data['tugas_list'] as $tugas) {
                    $pengumpulan = $this->Modul_model->get_pengumpulan_tugas(
                        $data['siswa']->siswa_id,
                        $tugas->tugas_id
                    );
                    $data['pengumpulan_tugas'][$tugas->tugas_id] = $pengumpulan;
                }
            }
        }

        // Ambil progress
        if (!empty($data['siswa'])) {
            $data['materi_progress'] = $this->Modul_model->get_progress(
                $data['siswa']->siswa_id,
                $materi_id
            );
        }

        $this->load->view('siswa/modul/index', $data);
    }

    public function kumpul_tugas() {
        if ($this->input->post()) {
            $tugas_id = $this->input->post('tugas_id');
            $materi_id = $this->input->post('materi_id');
            $catatan = $this->input->post('catatan');
            $link_pengumpulan = $this->input->post('link_pengumpulan');

            // Validasi input
            if (empty($tugas_id) || empty($materi_id)) {
                $this->session->set_flashdata('error', 'Data tugas tidak valid');
                redirect('siswa/modul/tugas/' . $materi_id);
            }

            // Ambil data tugas
            $tugas = $this->Modul_model->get_tugas_by_id($tugas_id);
            if (!$tugas) {
                $this->session->set_flashdata('error', 'Tugas tidak ditemukan');
                redirect('siswa/modul/tugas/' . $materi_id);
            }

            // Ambil data siswa
            $siswa = $this->Dashboard_model->get_siswa_data($this->user_id);
            if (!$siswa) {
                $this->session->set_flashdata('error', 'Data siswa tidak ditemukan');
                redirect('siswa/modul/tugas/' . $materi_id);
            }

            $deadline = strtotime($tugas->deadline);
            $now = time();

            // Cek deadline
            if ($now > $deadline) {
                $this->session->set_flashdata('error', 'Batas waktu pengumpulan telah berakhir.');
                redirect('siswa/modul/tugas/' . $materi_id);
            }

            // Konfigurasi upload file
            $config['upload_path'] = './uploads/tugas/';
            $config['allowed_types'] = 'zip|rar|pdf|doc|docx|jpg|jpeg|png';
            $config['max_size'] = 20480; // 20MB
            $config['file_name'] = 'tugas_' . $siswa->siswa_id . '_' . $tugas_id . '_' . time();
            $config['overwrite'] = false;

            // Buat folder jika belum ada
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->upload->initialize($config);

            $file_tugas = null;
            if (!empty($_FILES['file_tugas']['name'])) {
                if ($this->upload->do_upload('file_tugas')) {
                    $upload_data = $this->upload->data();
                    $file_tugas = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('siswa/modul/tugas/' . $materi_id);
                }
            }

            // Data untuk disimpan
            $data_pengumpulan = array(
                'tugas_id' => $tugas_id,
                'siswa_id' => $siswa->siswa_id,
                'file_tugas' => $file_tugas,
                'link_pengumpulan' => $link_pengumpulan,
                'catatan' => $catatan,
                'tanggal_kumpul' => date('Y-m-d H:i:s'),
                'status' => 'dikumpulkan'
            );

            // Cek apakah sudah pernah mengumpulkan
            $pengumpulan_exist = $this->Modul_model->get_pengumpulan_tugas($siswa->siswa_id, $tugas_id);
            
            if ($pengumpulan_exist) {
                // Hapus file lama jika ada
                if ($pengumpulan_exist->file_tugas && file_exists('./uploads/tugas/' . $pengumpulan_exist->file_tugas)) {
                    unlink('./uploads/tugas/' . $pengumpulan_exist->file_tugas);
                }
                
                // Update pengumpulan
                $update_result = $this->Modul_model->update_pengumpulan_tugas($pengumpulan_exist->pengumpulan_id, $data_pengumpulan);
                
                if ($update_result) {
                    $this->session->set_flashdata('success', 'Tugas berhasil diperbarui.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal memperbarui tugas');
                }
            } else {
                // Insert pengumpulan baru
                $insert_id = $this->Modul_model->insert_pengumpulan_tugas($data_pengumpulan);
                
                if ($insert_id) {
                    // Update progress materi
                    $this->Modul_model->update_progress_materi($siswa->siswa_id, $tugas->materi_id);
                    $this->session->set_flashdata('success', 'Tugas berhasil dikumpulkan.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menyimpan pengumpulan tugas');
                }
            }

            redirect('siswa/modul/tugas/' . $materi_id);
        }
    }

    public function download_template($tugas_id) {
        $tugas = $this->Modul_model->get_tugas_by_id($tugas_id);
        if ($tugas && $tugas->file_template) {
            $file_path = './uploads/templates/' . $tugas->file_template;
            if (file_exists($file_path)) {
                $this->load->helper('download');
                force_download($file_path, NULL);
            } else {
                $this->session->set_flashdata('error', 'File template tidak ditemukan.');
                redirect('siswa/modul/tugas/' . $tugas->materi_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada template untuk tugas ini.');
            redirect('siswa/dashboard');
        }
    }

    public function download_tugas($pengumpulan_id) {
        $pengumpulan = $this->Modul_model->get_pengumpulan_by_id($pengumpulan_id);
        if ($pengumpulan && $pengumpulan->file_tugas) {
            $file_path = './uploads/tugas/' . $pengumpulan->file_tugas;
            if (file_exists($file_path)) {
                $this->load->helper('download');
                force_download($file_path, NULL);
            } else {
                $this->session->set_flashdata('error', 'File tugas tidak ditemukan.');
                redirect('siswa/modul/tugas/' . $pengumpulan->materi_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file tugas.');
            redirect('siswa/dashboard');
        }
    }

    public function hapus_pengumpulan($pengumpulan_id) {
        $pengumpulan = $this->Modul_model->get_pengumpulan_by_id($pengumpulan_id);
        $siswa = $this->Dashboard_model->get_siswa_data($this->user_id);
        
        if ($pengumpulan && $siswa && $pengumpulan->siswa_id == $siswa->siswa_id) {
            // Hapus file jika ada
            if ($pengumpulan->file_tugas && file_exists('./uploads/tugas/' . $pengumpulan->file_tugas)) {
                unlink('./uploads/tugas/' . $pengumpulan->file_tugas);
            }
            // Hapus record
            $delete_result = $this->Modul_model->hapus_pengumpulan($pengumpulan_id);
            
            if ($delete_result) {
                $this->session->set_flashdata('success', 'Pengumpulan tugas berhasil dihapus.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus pengumpulan tugas.');
            }
            
            redirect('siswa/modul/tugas/' . $pengumpulan->materi_id);
        } else {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus pengumpulan tugas.');
            redirect('siswa/dashboard');
        }
    }

    // =============== FUNGSI AJAX ===============

    public function hapus_pengumpulan_ajax($pengumpulan_id) {
        header('Content-Type: application/json');
        
        if (!$this->user_id) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login']);
            return;
        }
        
        $pengumpulan = $this->Modul_model->get_pengumpulan_by_id($pengumpulan_id);
        $siswa = $this->Dashboard_model->get_siswa_data($this->user_id);
        
        if (!$pengumpulan || !$siswa || $pengumpulan->siswa_id != $siswa->siswa_id) {
            echo json_encode(['success' => false, 'message' => 'Tidak dapat menghapus pengumpulan']);
            return;
        }
        
        // Hapus file jika ada
        if ($pengumpulan->file_tugas && file_exists('./uploads/tugas/' . $pengumpulan->file_tugas)) {
            unlink('./uploads/tugas/' . $pengumpulan->file_tugas);
        }
        
        // Hapus record
        $result = $this->Modul_model->hapus_pengumpulan($pengumpulan_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Pengumpulan berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus pengumpulan']);
        }
    }

    public function update_progress_ajax() {
        header('Content-Type: application/json');
        
        if (!$this->user_id) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login']);
            return;
        }
        
        $materi_id = $this->input->post('materi_id');
        $status = $this->input->post('status');
        
        $siswa = $this->Dashboard_model->get_siswa_data($this->user_id);
        if (!$siswa) {
            echo json_encode(['success' => false, 'message' => 'Data siswa tidak ditemukan']);
            return;
        }
        
        $result = $this->Modul_model->update_progress_materi($siswa->siswa_id, $materi_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Progress berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal update progress']);
        }
    }

    public function get_materi_progress_ajax($materi_id) {
        header('Content-Type: application/json');
        
        if (!$this->user_id) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login']);
            return;
        }
        
        $siswa = $this->Dashboard_model->get_siswa_data($this->user_id);
        if (!$siswa) {
            echo json_encode(['success' => false, 'message' => 'Data siswa tidak ditemukan']);
            return;
        }
        
        $progress = $this->Modul_model->get_progress($siswa->siswa_id, $materi_id);
        
        echo json_encode([
            'success' => true,
            'progress' => $progress
        ]);
    }
}