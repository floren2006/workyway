<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'upload']);
        $this->load->helper(['url', 'form']);
    }
    
    // List materi per kursus
    public function index($kursus_id = null)
    {
        if (!$this->session->userdata('user_id')) redirect('login');

        if (!$kursus_id) {
            show_error('ID Kursus tidak ditemukan');
        }

        // Ambil semua materi dari tabel materi_kursus
        $this->db->where('kursus_id', $kursus_id);
        $data['materi'] = $this->db->get('materi_kursus')->result_array();
        $data['kursus_id'] = $kursus_id;

        $this->load->view('lpk/materi_list', $data);
    }

    public function upload()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('login');

        $kursus_id = $this->input->post('kursus_id');
        if (empty($kursus_id) || empty($_FILES['file_materi']['name'])) {
            $this->session->set_flashdata('error', 'Kursus dan file materi wajib diisi');
            redirect('lpk/kursus');
        }

        // Map ekstensi ke tipe_materi sesuai enum DB
        $allowed_types = [
            'pdf'  => 'pdf',
            'doc'  => 'pdf',
            'docx' => 'pdf',
            'txt'  => 'pdf',
            'ppt'  => 'pdf',
            'pptx' => 'pdf',
            'mp4'  => 'video',
            'mov'  => 'video',
            'avi'  => 'video',
            'zip'  => 'link',
            'rar'  => 'link'
        ];

        // Ambil ekstensi file
        $file_ext = strtolower(pathinfo($_FILES['file_materi']['name'], PATHINFO_EXTENSION));
        if (!array_key_exists($file_ext, $allowed_types)) {
            $this->session->set_flashdata('error', 'Tipe file tidak diizinkan');
            redirect('lpk/kursus');
        }

        $tipe_materi = $allowed_types[$file_ext];

        // Konfigurasi upload
        $upload_path = FCPATH . 'uploads/materi/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = implode('|', array_keys($allowed_types));
        $config['max_size']      = 20480; // 20 MB
        $config['encrypt_name']  = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_materi')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('lpk/kursus');
        }

        $fileData = $this->upload->data();

        // Simpan ke database
        $data = [
            'kursus_id'      => $kursus_id,
            'judul_materi'   => $fileData['orig_name'],
            'tipe_materi'    => $tipe_materi,
            'konten'         => $fileData['file_name'],
            'tanggal_upload' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('materi_kursus', $data);

        $this->session->set_flashdata('success', 'Materi berhasil diupload');
        redirect('lpk/materi/' . $kursus_id);
    }

    // Fungsi helper untuk upload file (DI PINDAHKAN KE PUBLIC/METHOD TERPISAH)
    private function _upload_file()
    {
        // Map ekstensi ke tipe_materi
        $allowed_types = [
            'pdf'  => 'pdf',
            'doc'  => 'pdf',
            'docx' => 'pdf',
            'txt'  => 'pdf',
            'ppt'  => 'pdf',
            'pptx' => 'pdf',
            'mp4'  => 'video',
            'mov'  => 'video',
            'avi'  => 'video',
            'zip'  => 'link',
            'rar'  => 'link'
        ];

        // Ambil ekstensi file
        $file_ext = strtolower(pathinfo($_FILES['file_materi']['name'], PATHINFO_EXTENSION));
        if (!array_key_exists($file_ext, $allowed_types)) {
            return ['status' => 'error', 'message' => 'Tipe file tidak diizinkan'];
        }

        $tipe_materi = $allowed_types[$file_ext];

        // Konfigurasi upload
        $upload_path = FCPATH . 'uploads/materi/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = implode('|', array_keys($allowed_types));
        $config['max_size']      = 20480; // 20 MB
        $config['encrypt_name']  = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_materi')) {
            return ['status' => 'error', 'message' => $this->upload->display_errors()];
        }

        $fileData = $this->upload->data();
        
        return [
            'status' => 'success',
            'file_name' => $fileData['file_name'],
            'original_name' => $fileData['orig_name'],
            'tipe_materi' => $tipe_materi
        ];
    }

    //Edit materi
    public function edit($materi_id = null)
    {
        if (!$this->session->userdata('user_id')) redirect('login');
        if (!$materi_id) show_error('ID Materi tidak ditemukan');

        // Ambil data materi
        $this->db->where('materi_id', $materi_id);
        $materi = $this->db->get('materi_kursus')->row_array();
        if (!$materi) show_error('Materi tidak ditemukan');

        // Jika form submit
        if ($this->input->post()) {
            $judul = $this->input->post('judul_materi');
            
            // Data update dasar
            $update_data = ['judul_materi' => $judul];
            
            // Cek apakah ada file baru diupload
            if (!empty($_FILES['file_materi']['name'])) {
                // Hapus file lama jika ada
                $old_file_path = FCPATH . 'uploads/materi/' . $materi['konten'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
                
                // Upload file baru - PERBAIKAN: gunakan $this->_upload_file()
                $upload_result = $this->_upload_file();
                
                if ($upload_result['status'] == 'success') {
                    $update_data['konten'] = $upload_result['file_name'];
                    $update_data['tipe_materi'] = $upload_result['tipe_materi'];
                } else {
                    $this->session->set_flashdata('error', 'Gagal upload file baru: ' . $upload_result['message']);
                    redirect('lpk/materi/edit/'.$materi_id);
                }
            }

            // Update DB
            $this->db->where('materi_id', $materi_id);
            $this->db->update('materi_kursus', $update_data);

            $this->session->set_flashdata('success', 'Materi berhasil diupdate');
            redirect('lpk/materi/'.$materi['kursus_id']);
        }

        $data['materi'] = $materi;
        $this->load->view('lpk/materi_edit_view', $data);
    }

    // Hapus materi
    public function hapus($materi_id = null)
    {
        if (!$this->session->userdata('user_id')) redirect('login');
        if (!$materi_id) show_error('ID Materi tidak ditemukan');

        // Ambil data materi untuk hapus file
        $materi = $this->db->get_where('materi_kursus', ['materi_id' => $materi_id])->row_array();
        if (!$materi) show_error('Materi tidak ditemukan');

        // Hapus file fisik
        $file_path = './uploads/materi/'.$materi['konten'];
        if (file_exists($file_path)) unlink($file_path);

        // Hapus data DB
        $this->db->delete('materi_kursus', ['materi_id' => $materi_id]);

        $this->session->set_flashdata('success', 'Materi berhasil dihapus');
        redirect('lpk/materi/'.$materi['kursus_id']);
    }
}