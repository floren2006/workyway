<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kursus extends CI_Controller {
    
    private $guru_id;
    private $user_id;
    private $guru_data;
    private $upload_path;
    private $kursus_model;

    public function __construct() {
        parent::__construct();
        
        // Load helper dan library
        $this->load->helper(['url', 'form', 'file', 'security']);
        $this->load->library(['session', 'form_validation', 'upload']);
        
        // Load model
        $this->load->model('guru/Kursus_model');
        $this->kursus_model = $this->Kursus_model;
        
        // Set upload path
        $this->upload_path = realpath(APPPATH . '../uploads/kursus/');
        
        // Buat folder jika belum ada
        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0755, true);
            file_put_contents($this->upload_path . '/index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
        
        // Cek session login
        $this->user_id = $this->session->userdata('user_id');
        $this->guru_id = $this->session->userdata('guru_id');
        
        if (!$this->user_id || $this->session->userdata('role') != 'guru') {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu!');
            redirect('login');
        }
        
        // Jika guru_id belum ada di session, ambil dari database
        if (!$this->guru_id) {
            $this->guru_id = $this->get_guru_id_by_user($this->user_id);
            if ($this->guru_id) {
                $this->session->set_userdata('guru_id', $this->guru_id);
            }
        }
        
        // Cek status verifikasi guru
        $this->check_guru_verification();
        
        // Ambil data guru
        $this->guru_data = $this->get_guru_data($this->guru_id);
    }

    private function get_guru_id_by_user($user_id) {
        $this->db->select('guru_id');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('guru_freelance');
        return $query->num_rows() > 0 ? $query->row()->guru_id : null;
    }

    private function check_guru_verification() {
        if ($this->guru_id) {
            $this->db->where('guru_id', $this->guru_id);
            $this->db->where('status_verifikasi', 'approved');
            $query = $this->db->get('guru_freelance');
            
            if ($query->num_rows() == 0) {
                $this->session->set_flashdata('error', 'Akun guru Anda belum diverifikasi. Anda tidak dapat membuat kursus.');
                redirect('guru/dashboard');
            }
        }
    }

    private function get_guru_data($guru_id) {
        $this->db->select('g.*, u.nama, u.email, u.foto_profil');
        $this->db->from('guru_freelance g');
        $this->db->join('users u', 'g.user_id = u.user_id');
        $this->db->where('g.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        
        if (empty($result)) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan!');
            redirect('guru/dashboard');
        }
        
        // Tambahkan URL foto profil
        if (!empty($result['foto_profil'])) {
            $result['foto_profil_url'] = base_url('uploads/profiles/' . $result['foto_profil']);
        } else {
            $result['foto_profil_url'] = base_url('assets/images/default-profile.jpg');
        }
        
        return $result;
    }

    public function index() {
        redirect('guru/kursus/list_kursus');
    }

    public function list_kursus() {
        // Cek database connection
        if (!$this->db->conn_id) {
            $this->session->set_flashdata('error', 'Koneksi database gagal!');
        }
        
        $data = [
            'title' => 'Manajemen Kursus',
            'guru' => $this->guru_data,
            'kursus' => $this->kursus_model->get_kursus_by_guru($this->guru_id),
            'kategori' => $this->db->get('kategori_kursus')->result_array()
        ];
        
        
        $this->load->view('guru/kursus', $data);
        $this->load->view('templates/guru_footer');
    }

    public function tambah() {
        // Cek database connection
        if (!$this->db->conn_id) {
            $this->session->set_flashdata('error', 'Koneksi database gagal!');
        }
        
        // Ambil kategori dari database
        $kategori = $this->db->get('kategori_kursus')->result_array();
        
        if (empty($kategori)) {
            $this->session->set_flashdata('error', 'Tidak ada kategori kursus yang tersedia. Silakan hubungi administrator.');
            redirect('guru/kursus/list_kursus');
        }
        
        $data = [
            'title' => 'Tambah Kursus Baru',
            'guru' => $this->guru_data,
            'kategori' => $kategori
        ];
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/tambah_kursus', $data);
        $this->load->view('templates/guru_footer');
    }

    public function simpan_kursus() {
        // Cek method
        if (!$this->input->post()) {
            $this->session->set_flashdata('error', 'Metode tidak diizinkan!');
            redirect('guru/kursus/tambah');
        }
        
        // Cek koneksi database
        if (!$this->db->conn_id) {
            $this->session->set_flashdata('error', 'Koneksi database gagal!');
            redirect('guru/kursus/tambah');
        }
        
        // Validasi form
        $this->form_validation->set_rules('judul_kursus', 'Judul Kursus', 'required|min_length[5]|max_length[150]|trim');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|min_length[20]|trim');
        $this->form_validation->set_rules('detail', 'Detail Kursus', 'required|min_length[30]|trim');
        $this->form_validation->set_rules('biaya', 'Biaya', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('durasi', 'Durasi', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $this->session->set_flashdata('error', $errors);
            redirect('guru/kursus/tambah');
        }
        
        // Handle upload gambar
        $gambar_kursus = 'default-course.jpg';
        
        if (isset($_FILES['gambar_kursus']) && !empty($_FILES['gambar_kursus']['name'])) {
            $config = [
                'upload_path' => $this->upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size' => 2048, // 2MB
                'file_name' => 'kursus_' . time() . '_' . rand(1000, 9999),
                'overwrite' => false,
                'remove_spaces' => true,
                'encrypt_name' => false
            ];
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('gambar_kursus')) {
                $upload_data = $this->upload->data();
                $gambar_kursus = $upload_data['file_name'];
                
                // Resize image jika perlu
                $this->load->library('image_lib');
                
                $config_resize = [
                    'image_library' => 'gd2',
                    'source_image' => $upload_data['full_path'],
                    'maintain_ratio' => TRUE,
                    'width' => 800,
                    'height' => 450,
                    'new_image' => $upload_data['full_path']
                ];
                
                $this->image_lib->initialize($config_resize);
                $this->image_lib->resize();
                $this->image_lib->clear();
                
            } else {
                $error = $this->upload->display_errors();
                log_message('error', 'Upload error: ' . $error);
                $this->session->set_flashdata('error', 'Gagal upload gambar: ' . $error);
                redirect('guru/kursus/tambah');
            }
        }
        
        // Cari ID berikutnya yang tersedia untuk menghindari konflik dengan ID 0
        $next_kursus_id = $this->get_next_kursus_id();
        
        // Siapkan data untuk database
        $data = [
            'kursus_id' => $next_kursus_id, // Explicitly set ID
            'kategori_id' => $this->input->post('kategori_id'),
            'guru_id' => $this->guru_id,
            'judul_kursus' => $this->input->post('judul_kursus'),
            'deskripsi' => $this->input->post('deskripsi'),
            'detail' => $this->input->post('detail'),
            'biaya' => $this->input->post('biaya'),
            'durasi' => $this->input->post('durasi'),
            'gambar_kursus' => $gambar_kursus,
            'status_kursus' => 'active', // Default aktif
            'rating_rata2' => 0.00,
            'tanggal_dibuat' => date('Y-m-d H:i:s')
        ];
        
        // Debug log
        log_message('info', 'Inserting kursus data: ' . print_r($data, true));
        
        // Insert ke database dengan transaksi
        $this->db->trans_start();
        
        try {
            $this->db->insert('kursus', $data);
            
            if ($this->db->affected_rows() > 0) {
                $kursus_id = $this->db->insert_id();
                
                $this->db->trans_complete();
                
                // Log sukses
                log_message('info', 'Kursus berhasil ditambahkan dengan ID: ' . $kursus_id);
                
                // Set flash data dan redirect
                $this->session->set_flashdata('success', 'Kursus berhasil ditambahkan!');
                redirect('guru/kursus/list_kursus');
                
            } else {
                throw new Exception('Tidak ada baris yang terpengaruh');
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            
            // Log error
            $error = $this->db->error();
            log_message('error', 'Database error: ' . print_r($error, true));
            log_message('error', 'Exception: ' . $e->getMessage());
            
            // Hapus file jika upload berhasil tapi insert gagal
            if ($gambar_kursus != 'default-course.jpg') {
                $file_path = $this->upload_path . '/' . $gambar_kursus;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            $this->session->set_flashdata('error', 'Gagal menambahkan kursus! Error: ' . $error['message']);
            redirect('guru/kursus/tambah');
        }
    }

    private function get_next_kursus_id() {
        // Query untuk mendapatkan ID maksimum yang ada
        $this->db->select_max('kursus_id', 'max_id');
        $query = $this->db->get('kursus');
        $result = $query->row();
        
        $max_id = isset($result->max_id) ? (int)$result->max_id : 0;
        
        // Jika max_id adalah 0 atau kurang dari 0, mulai dari 100
        if ($max_id <= 0) {
            return 100; // Mulai dari ID 100 untuk menghindari konflik
        }
        
        // Return ID berikutnya
        return $max_id + 1;
    }

    public function edit($kursus_id) {
        // Validasi ID
        if (!is_numeric($kursus_id) || $kursus_id <= 0) {
            $this->session->set_flashdata('error', 'ID kursus tidak valid!');
            redirect('guru/kursus/list_kursus');
        }
        
        $kursus = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        
        if (empty($kursus)) {
            $this->session->set_flashdata('error', 'Kursus tidak ditemukan atau tidak memiliki izin!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Parse durasi untuk form
        $durasi = $kursus['durasi'];
        $durasi_bulan = 1;
        if (preg_match('/(\d+(\.\d+)?)/', $durasi, $matches)) {
            $durasi_bulan = $matches[1];
        }
        $kursus['durasi_bulan'] = $durasi_bulan;
        
        // Map status
        $status_map = [
            'active' => 'aktif',
            'inactive' => 'nonaktif',
            'pending' => 'pending'
        ];
        $kursus['status_form'] = $status_map[$kursus['status_kursus']] ?? 'pending';
        
        $data = [
            'title' => 'Edit Kursus: ' . $kursus['judul_kursus'],
            'guru' => $this->guru_data,
            'kursus' => $kursus,
            'kategori' => $this->db->get('kategori_kursus')->result_array()
        ];
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/edit_kursus', $data);
        $this->load->view('templates/guru_footer');
    }

    public function update_kursus($kursus_id) {
        // Validasi ID
        if (!is_numeric($kursus_id) || $kursus_id <= 0) {
            $this->session->set_flashdata('error', 'ID kursus tidak valid!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Cek method
        if (!$this->input->post()) {
            $this->session->set_flashdata('error', 'Metode tidak diizinkan!');
            redirect('guru/kursus/edit/' . $kursus_id);
        }
        
        // Cek apakah kursus milik guru ini
        $kursus_lama = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        if (empty($kursus_lama)) {
            $this->session->set_flashdata('error', 'Kursus tidak ditemukan atau tidak memiliki izin!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Validasi form
        $this->form_validation->set_rules('judul_kursus', 'Judul Kursus', 'required|min_length[5]|max_length[150]|trim');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|min_length[20]|trim');
        $this->form_validation->set_rules('detail', 'Detail Kursus', 'required|min_length[30]|trim');
        $this->form_validation->set_rules('biaya', 'Biaya', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('durasi', 'Durasi', 'required');
        $this->form_validation->set_rules('status_kursus', 'Status', 'required|in_list[aktif,nonaktif,pending]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('guru/kursus/edit/' . $kursus_id);
        }
        
        // Handle upload gambar
        $gambar_kursus = $kursus_lama['gambar_kursus'] ?? 'default-course.jpg';
        
        if (isset($_FILES['gambar_kursus']) && !empty($_FILES['gambar_kursus']['name'])) {
            $config = [
                'upload_path' => $this->upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size' => 2048,
                'file_name' => 'kursus_' . time() . '_' . rand(1000, 9999),
                'overwrite' => false,
                'remove_spaces' => true
            ];
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('gambar_kursus')) {
                // Hapus gambar lama jika bukan default
                if ($gambar_kursus != 'default-course.jpg' && file_exists($this->upload_path . '/' . $gambar_kursus)) {
                    @unlink($this->upload_path . '/' . $gambar_kursus);
                }
                
                $upload_data = $this->upload->data();
                $gambar_kursus = $upload_data['file_name'];
                
                // Resize image
                $this->load->library('image_lib');
                $config_resize = [
                    'image_library' => 'gd2',
                    'source_image' => $upload_data['full_path'],
                    'maintain_ratio' => TRUE,
                    'width' => 800,
                    'height' => 450,
                    'new_image' => $upload_data['full_path']
                ];
                $this->image_lib->initialize($config_resize);
                $this->image_lib->resize();
                $this->image_lib->clear();
                
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('guru/kursus/edit/' . $kursus_id);
            }
        }
        
        // Map status form ke database
        $status_map = [
            'aktif' => 'active',
            'nonaktif' => 'inactive',
            'pending' => 'pending'
        ];
        $status_db = $status_map[$this->input->post('status_kursus')] ?? 'active';
        
        // Prepare data
        $data = [
            'kategori_id' => $this->input->post('kategori_id'),
            'judul_kursus' => $this->input->post('judul_kursus'),
            'deskripsi' => $this->input->post('deskripsi'),
            'detail' => $this->input->post('detail'),
            'biaya' => $this->input->post('biaya'),
            'durasi' => $this->input->post('durasi'),
            'gambar_kursus' => $gambar_kursus,
            'status_kursus' => $status_db
        ];
        
        // Update database
        $result = $this->kursus_model->update_kursus($kursus_id, $data, $this->guru_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Kursus berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui kursus!');
        }
        
        redirect('guru/kursus/list_kursus');
    }

    public function hapus($kursus_id) {
        // Validasi ID
        if (!is_numeric($kursus_id) || $kursus_id <= 0) {
            $this->session->set_flashdata('error', 'ID kursus tidak valid!');
            redirect('guru/kursus/list_kursus');
        }
        
        $kursus = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        
        if (!empty($kursus)) {
            // Hapus gambar jika bukan default
            if ($kursus['gambar_kursus'] != 'default-course.jpg' && file_exists($this->upload_path . '/' . $kursus['gambar_kursus'])) {
                @unlink($this->upload_path . '/' . $kursus['gambar_kursus']);
            }
            
            // Hapus dari database
            $result = $this->kursus_model->delete_kursus($kursus_id, $this->guru_id);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Kursus berhasil dihapus!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus kursus!');
            }
        } else {
            $this->session->set_flashdata('error', 'Kursus tidak ditemukan!');
        }
        
        redirect('guru/kursus/list_kursus');
    }

    public function hapus_gambar($kursus_id) {
        // Validasi ID
        if (!is_numeric($kursus_id) || $kursus_id <= 0) {
            $this->session->set_flashdata('error', 'ID kursus tidak valid!');
            redirect('guru/kursus/list_kursus');
        }
        
        $kursus = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        
        if (!empty($kursus) && $kursus['gambar_kursus'] != 'default-course.jpg') {
            $gambar_path = $this->upload_path . '/' . $kursus['gambar_kursus'];
            
            if (file_exists($gambar_path)) {
                @unlink($gambar_path);
            }
            
            // Update ke database
            $this->kursus_model->update_kursus($kursus_id, ['gambar_kursus' => 'default-course.jpg'], $this->guru_id);
            
            $this->session->set_flashdata('success', 'Gambar kursus berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus gambar!');
        }
        
        redirect('guru/kursus/edit/' . $kursus_id);
    }

    public function update_status($kursus_id, $status) {
        // Validasi ID
        if (!is_numeric($kursus_id) || $kursus_id <= 0) {
            $this->session->set_flashdata('error', 'ID kursus tidak valid!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Validasi status
        $valid_status = ['active', 'inactive', 'pending'];
        if (!in_array($status, $valid_status)) {
            $this->session->set_flashdata('error', 'Status tidak valid!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Update status
        $result = $this->kursus_model->update_kursus($kursus_id, ['status_kursus' => $status], $this->guru_id);
        
        if ($result) {
            $status_text = [
                'active' => 'diaktifkan',
                'inactive' => 'dinonaktifkan', 
                'pending' => 'ditunda'
            ];
            $this->session->set_flashdata('success', "Kursus berhasil {$status_text[$status]}!");
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate status kursus!');
        }
        
        redirect('guru/kursus/list_kursus');
    }

    // ========== UPLOAD MATERI METHODS ==========
    
    public function upload_video($kursus_id) {
        // Cek izin akses kursus
        $kursus = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        if (!$kursus) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kursus ini!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Validasi
        $this->form_validation->set_rules('judul_video', 'Judul Video', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('guru/kursus/list_kursus');
        }
        
        // Upload video
        $video_path = realpath(APPPATH . '../uploads/videos/');
        
        // Buat folder jika belum ada
        if (!is_dir($video_path)) {
            mkdir($video_path, 0755, true);
            file_put_contents($video_path . '/index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
        
        $config = [
            'upload_path' => $video_path,
            'allowed_types' => 'mp4|avi|mov|wmv|flv|mkv',
            'max_size' => 100 * 1024, // 100MB
            'file_name' => 'video_' . time() . '_' . rand(1000, 9999),
            'overwrite' => false,
            'remove_spaces' => true
        ];
        
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload('file_video')) {
            $upload_data = $this->upload->data();
            
            // Simpan ke tabel materi_kursus
            $data_materi = [
                'kursus_id' => $kursus_id,
                'judul_materi' => $this->input->post('judul_video'),
                'tipe_materi' => 'video',
                'konten' => $upload_data['file_name'],
                'tanggal_upload' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('materi_kursus', $data_materi);
            
            $this->session->set_flashdata('success', 'Video berhasil diupload!');
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        }
        
        redirect('guru/kursus/list_kursus');
    }
    
    public function upload_materi($kursus_id) {
        // Cek izin akses kursus
        $kursus = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        if (!$kursus) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kursus ini!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Validasi
        $this->form_validation->set_rules('judul_materi', 'Judul Materi', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('guru/kursus/list_kursus');
        }
        
        // Upload Materi
        $materi_path = realpath(APPPATH . '../uploads/materi/');
        
        // Buat folder jika belum ada
        if (!is_dir($materi_path)) {
            mkdir($materi_path, 0755, true);
            file_put_contents($materi_path . '/index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
        
        $config = [
            'upload_path' => $materi_path,
            'allowed_types' => 'pdf|doc|docx|txt|ppt|pptx|xls|xlsx',
            'max_size' => 10 * 1024, // 10MB
            'file_name' => 'materi_' . time() . '_' . rand(1000, 9999),
            'overwrite' => false,
            'remove_spaces' => true
        ];
        
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload('file_materi')) {
            $upload_data = $this->upload->data();
            
            // Tentukan tipe berdasarkan ekstensi
            $file_ext = pathinfo($upload_data['file_name'], PATHINFO_EXTENSION);
            $tipe_materi = 'pdf'; // Default untuk semua dokumen
            
            // Simpan ke tabel materi_kursus
            $data_materi = [
                'kursus_id' => $kursus_id,
                'judul_materi' => $this->input->post('judul_materi'),
                'tipe_materi' => $tipe_materi,
                'konten' => $upload_data['file_name'],
                'tanggal_upload' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('materi_kursus', $data_materi);
            
            $this->session->set_flashdata('success', 'Materi berhasil diupload!');
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        }
        
        redirect('guru/kursus/list_kursus');
    }
    
    /**
     * BUAT TUGAS (Menggantikan buat_kuis)
     * Tugas akan disimpan ke tabel 'tugas' sesuai dengan materi_id
     */
    public function buat_tugas($kursus_id) {
        // Cek izin akses kursus
        $kursus = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        if (!$kursus) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kursus ini!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Validasi
        $this->form_validation->set_rules('judul_tugas', 'Judul Tugas', 'required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('materi_id', 'Materi', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $this->session->set_flashdata('error', $errors);
            redirect('guru/kursus/list_kursus');
        }
        
        // Cek apakah materi_id valid dan milik kursus ini
        $materi_id = $this->input->post('materi_id');
        $this->db->where('materi_id', $materi_id);
        $this->db->where('kursus_id', $kursus_id);
        $materi = $this->db->get('materi_kursus')->row_array();
        
        if (!$materi) {
            $this->session->set_flashdata('error', 'Materi tidak valid atau tidak ditemukan!');
            redirect('guru/kursus/list_kursus');
        }
        
        // Upload file tugas (opsional)
        $file_template = NULL;
        
        if (isset($_FILES['file_tugas']) && !empty($_FILES['file_tugas']['name'])) {
            $tugas_path = realpath(APPPATH . '../uploads/tugas/');
            
            // Buat folder jika belum ada
            if (!is_dir($tugas_path)) {
                mkdir($tugas_path, 0755, true);
                file_put_contents($tugas_path . '/index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
            }
            
            $config = [
                'upload_path' => $tugas_path,
                'allowed_types' => 'pdf|doc|docx|txt|zip|rar',
                'max_size' => 10 * 1024, // 10MB
                'file_name' => 'tugas_' . time() . '_' . rand(1000, 9999),
                'overwrite' => false,
                'remove_spaces' => true
            ];
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('file_tugas')) {
                $upload_data = $this->upload->data();
                $file_template = $upload_data['file_name'];
            } else {
                $error_msg = $this->upload->display_errors();
                $this->session->set_flashdata('error', 'Gagal upload file tugas: ' . $error_msg);
                redirect('guru/kursus/list_kursus');
            }
        }
        
        // Siapkan data untuk tabel tugas
        $deadline = $this->input->post('deadline') ?: date('Y-m-d H:i:s', strtotime('+1 week'));
        $max_score = $this->input->post('max_score') ?: 100;
        $tipe_tugas = $this->input->post('tipe_tugas') ?: 'individual';
        
        $tugas_data = [
            'materi_id' => $materi_id,
            'judul_tugas' => $this->input->post('judul_tugas'),
            'deskripsi' => $this->input->post('deskripsi_tugas') ?? '',
            'deadline' => $deadline,
            'tipe_tugas' => $tipe_tugas,
            'file_template' => $file_template,
            'max_score' => $max_score,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Simpan ke tabel tugas
        $this->db->insert('tugas', $tugas_data);
        
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Tugas berhasil dibuat!');
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat tugas!');
        }
        
        redirect('guru/kursus/list_kursus');
    }
    
    /**
     * GET MATERI BY KURSUS ID (AJAX)
     * Untuk dropdown pilihan materi
     */
    public function get_materi_by_kursus($kursus_id) {
        // Cek apakah kursus milik guru ini
        $kursus = $this->kursus_model->get_kursus_by_id($kursus_id, $this->guru_id);
        if (!$kursus) {
            echo json_encode(['error' => 'Akses ditolak']);
            return;
        }
        
        // Ambil materi dari kursus ini
        $this->db->select('materi_id, judul_materi, tipe_materi');
        $this->db->where('kursus_id', $kursus_id);
        $this->db->order_by('judul_materi', 'ASC');
        $materi = $this->db->get('materi_kursus')->result_array();
        
        echo json_encode($materi);
    }
}
?>