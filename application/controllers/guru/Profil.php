<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {
    
    protected $guru_id = 1;
    
    public function __construct() {
        parent::__construct();
        
        // Load library dan helper yang diperlukan
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);
        $this->load->model('guru/profil_model');
        
        // Cek apakah user sudah login dan berrole guru
        $this->check_login();
        
        // Pastikan folder profiles ada
        $profiles_dir = FCPATH . 'uploads/profiles/';
        if (!is_dir($profiles_dir)) {
            mkdir($profiles_dir, 0755, true);
        }
    }
    
    /**
     * Cek status login user
     */
    private function check_login() {
        // Periksa apakah session sudah ada
        if (!$this->session->has_userdata('logged_in')) {
            redirect('login');
        }
        
        // Periksa role user
        if ($this->session->userdata('role') !== 'guru') {
            show_error('Akses ditolak. Halaman ini hanya untuk guru.', 403);
        }
    }

    public function index() {
        $this->profil();
    }

    public function profil() {
        $guru_id = $this->session->userdata('guru_id');

        if (!$guru_id) {
            redirect('login');
        }

        // Ambil data lengkap guru
        $data['guru'] = $this->profil_model->get_complete_guru_data($guru_id);

        if (!$data['guru']) {
            show_error('Data guru tidak ditemukan');
        }

        // Decode keahlian dari JSON
        $data['keahlian_array'] = !empty($data['guru']['keahlian']) 
            ? json_decode($data['guru']['keahlian'], true) 
            : [];
        
        // Ambil kursus yang diajar
        $this->load->database();
        $data['kursus'] = $this->get_kursus_by_guru($guru_id);
        
        // Format tanggal
        if (!empty($data['guru']['tanggal_daftar'])) {
            $data['guru']['tanggal_daftar_formatted'] = date('d F Y', strtotime($data['guru']['tanggal_daftar']));
        } else {
            $data['guru']['tanggal_daftar_formatted'] = 'Belum tersedia';
        }
        
        // Hitung statistik tambahan
        $data['statistik'] = $this->get_guru_statistik($guru_id);
        
        // Tambahkan logika untuk portofolio (HANYA PDF dari database)
        $this->tambahkan_portofolio($data);
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/profil', $data);
        $this->load->view('templates/guru_footer');
    }

    public function edit() {
        $guru_id = $this->session->userdata('guru_id');

        if (!$guru_id) {
            redirect('login');
        }

        $data['guru'] = $this->profil_model->get_complete_guru_data($guru_id);
        $data['title'] = 'Edit Profil Guru';
        
        // Initialize messages
        $data['message'] = '';
        $data['message_type'] = '';
        
        // Proses form submit
        if ($this->input->method() === 'post') {
            $result = $this->process_edit_profile($guru_id);
            
            if ($result['status'] === 'success') {
                // Set flashdata untuk notifikasi sukses
                $this->session->set_flashdata('message', 'Profil berhasil diperbarui!');
                $this->session->set_flashdata('message_type', 'success');
                
                // Redirect ke halaman profil
                redirect('guru/profil');
                return;
            } else {
                $data['message'] = $result['message'];
                $data['message_type'] = 'error';
            }
        }
        
        // Decode keahlian untuk form
        $data['keahlian_str'] = '';
        if (!empty($data['guru']['keahlian'])) {
            $keahlian_array = json_decode($data['guru']['keahlian'], true);
            if (is_array($keahlian_array)) {
                $data['keahlian_str'] = implode(', ', $keahlian_array);
            }
        }
        
        // Setup portofolio data untuk view
        $portofolio = $data['guru']['portofolio'] ?? null;
        if (!empty($portofolio)) {
            $data['portofolio_file_name'] = $portofolio;
        } else {
            $data['portofolio_file_name'] = null;
        }
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/edit_profil', $data);
        $this->load->view('templates/guru_footer');
    }
    
    private function process_edit_profile($guru_id) {
        // Validasi
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('telepon', 'Telepon', 'trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim');
        $this->form_validation->set_rules('pengalaman', 'Pengalaman', 'trim');
        $this->form_validation->set_rules('keahlian', 'Keahlian', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            return [
                'status' => 'error',
                'message' => validation_errors()
            ];
        }
        
        // Data untuk tabel users
        $data_user = [
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'telepon' => $this->input->post('telepon'),
            'alamat' => $this->input->post('alamat')
        ];
        
        // Handle upload foto profil
        $foto_result = $this->handle_foto_profil($guru_id);
        if ($foto_result !== null && !is_array($foto_result)) {
            $data_user['foto_profil'] = $foto_result;
        } elseif (is_array($foto_result)) {
            return [
                'status' => 'error',
                'message' => $foto_result['message']
            ];
        }
        
        // Data untuk tabel guru_freelance
        $keahlian = $this->input->post('keahlian');
        $keahlian_array = explode(',', $keahlian);
        $keahlian_array = array_map('trim', $keahlian_array);
        $keahlian_array = array_filter($keahlian_array);
        
        $data_guru = [
            'pengalaman' => $this->input->post('pengalaman'),
            'keahlian' => json_encode(array_values($keahlian_array))
        ];
        
        // Handle portofolio (HANYA PDF)
        $portofolio_result = $this->handle_portofolio($guru_id);
        
        // Jika hasil adalah string (nama file), simpan ke database
        if ($portofolio_result !== null && !is_array($portofolio_result)) {
            $data_guru['portofolio'] = $portofolio_result;
        } elseif ($portofolio_result === null) {
            // Jika null, jangan ubah field portofolio (biarkan apa adanya)
        } else {
            // Jika array, berarti ada error upload
            return [
                'status' => 'error',
                'message' => $portofolio_result['message']
            ];
        }
        
        // Proses update database
        if ($this->profil_model->update_guru_profile($guru_id, $data_guru, $data_user)) {
            return [
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui!'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal memperbarui profil. Silakan coba lagi.'
            ];
        }
    }
    
    /**
     * Handle upload foto profil
     */
    private function handle_foto_profil($guru_id) {
        // Ambil data saat ini dari database
        $current_data = $this->profil_model->get_complete_guru_data($guru_id);
        $current_foto = $current_data['foto_profil'] ?? null;
        
        // Jika ada file yang diupload
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === 0) {
            // Konfigurasi upload untuk gambar
            $config['upload_path'] = FCPATH . 'uploads/profiles/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = 'profile_' . $guru_id . '_' . time();
            
            // Jika folder tidak ada, buat
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('foto_profil')) {
                $upload_data = $this->upload->data();
                
                // Hapus file lama jika ada dan bukan default
                if ($current_foto && $current_foto !== 'default.jpg' && 
                    file_exists(FCPATH . 'uploads/profiles/' . $current_foto)) {
                    unlink(FCPATH . 'uploads/profiles/' . $current_foto);
                }
                
                // Simpan nama file ke database
                return $upload_data['file_name'];
                
            } else {
                // Jika upload gagal, kembalikan error
                return [
                    'status' => 'error',
                    'message' => $this->upload->display_errors()
                ];
            }
        }
        
        // Jika checkbox "hapus foto" dicentang
        if ($this->input->post('hapus_foto') === '1') {
            // Hapus file lama jika ada dan bukan default
            if ($current_foto && $current_foto !== 'default.jpg' && 
                file_exists(FCPATH . 'uploads/profiles/' . $current_foto)) {
                unlink(FCPATH . 'uploads/profiles/' . $current_foto);
            }
            // Set ke default
            return 'default.jpg';
        }
        
        // Jika tidak ada file baru yang diupload dan tidak ada permintaan hapus,
        // return null (tidak ada perubahan pada foto)
        return null;
    }
    
    /**
     * Handle upload portofolio PDF dan simpan ke database
     */
    private function handle_portofolio($guru_id) {
        // Ambil data saat ini dari database
        $current_data = $this->profil_model->get_complete_guru_data($guru_id);
        $current_portofolio = $current_data['portofolio'] ?? null;
        
        // Jika ada file yang diupload
        if (isset($_FILES['portofolio_file']) && $_FILES['portofolio_file']['error'] === 0) {
            // Konfigurasi upload hanya untuk PDF
            $config['upload_path'] = FCPATH . 'uploads/portofolio/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 10240; // 10MB
            $config['file_name'] = 'portofolio_' . $guru_id . '_' . time() . '.pdf';
            
            // Jika folder tidak ada, buat
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('portofolio_file')) {
                $upload_data = $this->upload->data();
                
                // Hapus file lama jika ada
                if ($current_portofolio && file_exists(FCPATH . 'uploads/portofolio/' . $current_portofolio)) {
                    unlink(FCPATH . 'uploads/portofolio/' . $current_portofolio);
                }
                
                // Simpan nama file ke database
                return $upload_data['file_name'];
                
            } else {
                // Jika upload gagal, kembalikan error
                return [
                    'status' => 'error',
                    'message' => $this->upload->display_errors()
                ];
            }
        }
        
        // Jika checkbox "hapus portofolio" dicentang
        if ($this->input->post('hapus_portofolio') === '1') {
            // Hapus file lama jika ada
            if ($current_portofolio && file_exists(FCPATH . 'uploads/portofolio/' . $current_portofolio)) {
                unlink(FCPATH . 'uploads/portofolio/' . $current_portofolio);
            }
            // Kosongkan field portofolio di database
            return '';
        }
        
        // Jika tidak ada file baru yang diupload dan tidak ada permintaan hapus,
        // return null (tidak ada perubahan pada portofolio)
        return null;
    }
    
    /**
     * Fungsi untuk menangani portofolio di view (HANYA PDF)
     */
    private function tambahkan_portofolio(&$data) {
        $portofolio = $data['guru']['portofolio'] ?? null;
        
        if (!empty($portofolio)) {
            // Buat path untuk file portofolio
            $data['portofolio_file'] = base_url('uploads/portofolio/' . $portofolio);
            $data['portofolio_name'] = $portofolio;
            
            // Cek apakah file tersedia
            $data['portofolio_exists'] = file_exists(FCPATH . 'uploads/portofolio/' . $portofolio);
            
            // Pastikan ekstensi file adalah PDF
            $ext = pathinfo($portofolio, PATHINFO_EXTENSION);
            $data['portofolio_ext'] = strtolower($ext);
        } else {
            $data['portofolio_file'] = null;
            $data['portofolio_exists'] = false;
        }
    }
    
    /**
     * Hitung statistik tambahan untuk profil guru
     */
    private function get_guru_statistik($guru_id) {
        $this->load->database();
        $statistik = [];
        
        // Jumlah total kursus aktif
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'active');
        $statistik['total_kursus'] = $this->db->count_all_results('kursus');
        
        // Jumlah total siswa
        $this->db->select('COUNT(DISTINCT e.siswa_id) as total');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        $statistik['total_siswa'] = $result['total'] ?? 0;
        
        // Rata-rata rating
        $this->db->select('AVG(e.rating) as avg_rating');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('e.rating IS NOT NULL');
        $result = $this->db->get()->row_array();
        $statistik['avg_rating'] = round($result['avg_rating'] ?? 0, 1);
        
        // Total pendapatan
        $this->db->select('SUM(t.gaji) as total');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('t.status', 'success');
        $result = $this->db->get()->row_array();
        $statistik['total_pendapatan'] = $result['total'] ?? 0;
        
        return $statistik;
    }
    
    /**
     * Ambil kursus berdasarkan guru_id
     */
    private function get_kursus_by_guru($guru_id, $limit = null) {
        $this->load->database();
        $this->db->select('k.*, kk.nama_kategori');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->order_by('k.tanggal_dibuat', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result_array();
    }
}