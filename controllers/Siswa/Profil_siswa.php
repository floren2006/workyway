<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_siswa extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form', 'download']);
        $this->load->library(['session', 'form_validation']);
        $this->load->database();
        $this->load->model('siswa/Model_siswa');

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        if ($this->session->userdata('role') !== 'siswa') {
            show_error('Akses ditolak', 403);
        }

        $this->user_id = $this->session->userdata('user_id');

        $this->create_upload_folders();
    }
    
    private function create_upload_folders() {
        $folders = [
            'uploads/profiles',
            'uploads/sertifikat'
        ];  
        
        foreach ($folders as $folder) {
            if (!is_dir(FCPATH . $folder)) {
                mkdir(FCPATH . $folder, 0775, true);
                $index_file = FCPATH . $folder . '/index.html';
                if (!file_exists($index_file)) {
                    file_put_contents($index_file, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
                }
            }
        }
    }

    public function index() {
        $data['user'] = $this->Model_siswa->get_user_by_id($this->user_id);
        
        if (!$data['user']) {
            show_error('User tidak ditemukan', 404);
        }
        
        $siswa = $this->Model_siswa->get_siswa_by_user_id($this->user_id);
        
        if (!$siswa) {
            show_error('Data siswa tidak ditemukan', 404);
        }
        
        // Dapatkan semua enrollment untuk riwayat kursus
        $data['riwayat_kursus'] = $this->Model_siswa->get_riwayat_kursus($siswa->siswa_id);
        
        // Proses setiap kursus untuk mendapatkan data lengkap
        $data['processed_kursus'] = [];
        if ($data['riwayat_kursus']) {
            foreach ($data['riwayat_kursus'] as $kursus) {
                $kursus_data = $this->Model_siswa->get_data_sertifikat($kursus->enrollment_id, $siswa->siswa_id);
                $data['processed_kursus'][] = $kursus_data;
            }
        }
        
        $data['siswa_data'] = $siswa;
        
        $this->load->view('siswa/profil_siswa/index', $data);
    }

    public function update_profil() {
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('telepon', 'Nomor Telepon', 'trim');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('siswa/profil_siswa');
            return;
        }
        
        $user_data = [
            'nama' => $this->input->post('nama'),
            'telepon' => $this->input->post('telepon')
        ];
        
        if (!empty($_FILES['foto_profil']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/profiles/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0775, true);
            }
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('foto_profil')) {
                $upload_data = $this->upload->data();
                $user_data['foto_profil'] = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('siswa/profil_siswa');
                return;
            }
        }
        
        $result = $this->Model_siswa->update_user($this->user_id, $user_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profil');
        }
        
        redirect('siswa/profil_siswa');
    }

    public function update_password() {
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|trim|matches[password_baru]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error_password', validation_errors());
            redirect('siswa/profil_siswa');
            return;
        }
        
        $password_lama = $this->input->post('password_lama');
        $password_baru = $this->input->post('password_baru');
        
        $user = $this->Model_siswa->get_user_by_id($this->user_id);
        
        if (md5($password_lama) !== $user->password) {
            $this->session->set_flashdata('error_password', 'Password lama salah');
            redirect('siswa/profil_siswa');
            return;
        }
        
        $data = ['password' => md5($password_baru)];
        $result = $this->Model_siswa->update_user($this->user_id, $data);
        
        if ($result) {
            $this->session->set_flashdata('success_password', 'Password berhasil diubah');
        } else {
            $this->session->set_flashdata('error_password', 'Gagal mengubah password');
        }
        
        redirect('siswa/profil_siswa');
    }

    public function download_sertifikat($enrollment_id) {
        $siswa = $this->Model_siswa->get_siswa_by_user_id($this->user_id);
        
        if (!$siswa) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan');
            redirect('siswa/profil_siswa');
            return;
        }
        
        // Cek apakah bisa download
        if (!$this->Model_siswa->bisa_download_sertifikat($enrollment_id, $siswa->siswa_id)) {
            $this->session->set_flashdata('error', 'Belum memenuhi syarat untuk download sertifikat. Kursus harus selesai dan progress minimal 80%.');
            redirect('siswa/profil_siswa');
            return;
        }
        
        // Dapatkan data untuk sertifikat
        $sertifikat_data = $this->Model_siswa->get_data_sertifikat($enrollment_id, $siswa->siswa_id);
        
        if (!$sertifikat_data) {
            $this->session->set_flashdata('error', 'Data sertifikat tidak ditemukan');
            redirect('siswa/profil_siswa');
            return;
        }
        
        // Generate sertifikat
        $filename = $this->generate_sertifikat($sertifikat_data);
        
        // Update nama file sertifikat di database
        $this->Model_siswa->update_sertifikat_filename($enrollment_id, $filename);
        
        $file_path = FCPATH . 'uploads/sertifikat/' . $filename;
        
        if (file_exists($file_path)) {
            force_download($filename, file_get_contents($file_path));
        } else {
            $this->session->set_flashdata('error', 'Gagal generate sertifikat');
            redirect('siswa/profil_siswa');
        }
    }

    private function generate_sertifikat($data) {
        // Standar nama file
        $filename = 'sertifikat_' . 
                   preg_replace('/[^a-zA-Z0-9]/', '_', $data['nama_siswa']) . '_' . 
                   preg_replace('/[^a-zA-Z0-9]/', '_', $data['judul_kursus']) . '_' . 
                   date('YmdHis') . '.html';
        
        $html_content = $this->create_certificate_html($data);
        
        $file_path = FCPATH . 'uploads/sertifikat/' . $filename;
        file_put_contents($file_path, $html_content);
        
        return $filename;
    }
    
    private function create_certificate_html($data) {
        $nama_siswa = htmlspecialchars(strtoupper($data['nama_siswa']));
        $judul_kursus = htmlspecialchars($data['judul_kursus']);
        $durasi = htmlspecialchars($data['durasi']);
        $nilai_display = number_format($data['nilai_akhir'], 2);
        $progress = $data['progress'];
        $tanggal_mulai = date('d F Y', strtotime($data['tanggal_daftar']));
        $tanggal_selesai = date('d F Y', strtotime($data['tanggal_selesai']));
        $kategori = $data['kategori'];
        $deskripsi = $data['deskripsi'];
        $info_tugas = $data['info_tugas'];
        
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SERTIFIKAT - ' . $judul_kursus . '</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .certificate {
            background: white;
            width: 210mm;
            height: 297mm;
            padding: 40px;
            border: 20px solid #2c3e50;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .certificate:before {
            content: "";
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 2px solid #b8860b;
        }
        
        .header h1 {
            color: #2c3e50;
            font-size: 36px;
            margin: 30px 0 10px;
            text-transform: uppercase;
        }
        
        .header h2 {
            color: #b8860b;
            font-size: 22px;
            margin: 0 0 30px;
            font-style: italic;
        }
        
        .content {
            margin: 40px 0;
        }
        
        .student-name {
            font-size: 42px;
            font-weight: bold;
            color: #2c3e50;
            margin: 40px 0;
            padding: 30px 0;
            border-top: 2px solid #b8860b;
            border-bottom: 2px solid #b8860b;
            text-transform: uppercase;
        }
        
        .kategori-container {
            margin: 30px auto;
            padding: 20px;
            background: ' . $kategori['color'] . ';
            border-radius: 15px;
            color: white;
            max-width: 500px;
        }
        
        .kategori-text {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 10px;
        }
        
        .grade-badge {
            font-size: 36px;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            padding: 10px 30px;
            border-radius: 10px;
            display: inline-block;
            margin: 10px 0;
        }
        
        .details {
            margin: 40px 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .detail-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .detail-title {
            font-size: 14px;
            color: #718096;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 18px;
            font-weight: 600;
            color: #2c3748;
        }
        
        .signature {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 3px double #b8860b;
        }
        
        .signature-content {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3748;
            margin-top: 20px;
        }
        
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            
            .certificate {
                border: 15px solid #2c3e50;
                padding: 30px;
                box-shadow: none;
                page-break-after: always;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="certificate">
        <div class="header">
            <h1>SERTIFIKAT PRESTASI</h1>
            <h2>WorkyWay Learning Platform</h2>
        </div>
        
        <div class="content">
            <p style="font-size: 20px; color: #555; margin-bottom: 30px; font-style: italic;">
                SERTIFIKAT INI DIBERIKAN KEPADA
            </p>
            
            <div class="student-name">' . $nama_siswa . '</div>
            
            <p style="font-size: 18px; color: #555; line-height: 1.6; margin: 30px auto; max-width: 600px;">
                Atas keberhasilan menyelesaikan kursus <strong>' . $judul_kursus . '</strong> 
                dengan durasi <strong>' . $durasi . '</strong>
            </p>
            
            <div class="kategori-container">
                <p class="kategori-text">' . $kategori['nama'] . '</p>
                <div class="grade-badge">' . $kategori['grade'] . '</div>
                <p style="margin: 10px 0 0; font-size: 16px; opacity: 0.95;">
                    <i class="' . $kategori['icon'] . '"></i> ' . $kategori['deskripsi'] . '
                </p>
            </div>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px auto; max-width: 500px;">
                <p style="margin: 5px 0; font-size: 16px;">
                    <strong>Nilai Akhir:</strong> ' . $nilai_display . ' / 100
                </p>
                <p style="margin: 5px 0; font-size: 16px;">
                    <strong>Progress:</strong> ' . $progress . '%
                </p>
                <p style="margin: 5px 0; font-size: 14px; color: #666;">
                    Tugas: ' . $info_tugas['tugas_dinilai'] . ' dinilai dari ' . $info_tugas['total_tugas'] . ' total
                </p>
            </div>
            
            <p style="background: linear-gradient(135deg, #f8f9fa 0%, #e0e7ff 100%); 
                      padding: 20px; border-radius: 10px; margin: 30px auto; max-width: 600px;
                      font-size: 16px; color: #2c3e50; line-height: 1.6; text-align: left;">
                <i class="fas fa-quote-left" style="color: ' . $kategori['color'] . '; margin-right: 10px;"></i>
                ' . $deskripsi . '
            </p>
            
            <div class="details">
                <div class="detail-item">
                    <div class="detail-title">Tanggal Mulai</div>
                    <div class="detail-value">' . $tanggal_mulai . '</div>
                </div>
                <div class="detail-item">
                    <div class="detail-title">Tanggal Selesai</div>
                    <div class="detail-value">' . $tanggal_selesai . '</div>
                </div>
                <div class="detail-item">
                    <div class="detail-title">Tanggal Terbit</div>
                    <div class="detail-value">' . date('d F Y') . '</div>
                </div>
            </div>
        </div>
        
        <div class="signature">
            <div class="signature-content">
                <div class="signature-box">
                    <div style="height: 60px; border-bottom: 2px solid #2c3e50; width: 200px; margin: 0 auto 10px;"></div>
                    <div class="signature-name">Jennie Kim, S.Kom</div>
                    <div style="color: #718096; font-size: 14px;">Direktur WorkyWay</div>
                </div>
                
                <div class="signature-box">
                    <div style="height: 60px; border-bottom: 2px solid #2c3e50; width: 200px; margin: 0 auto 10px;"></div>
                    <div class="signature-name">Pablo Gavi, M.Kom</div>
                    <div style="color: #718096; font-size: 14px;">Direktur Utama WorkyWay</div>
                </div>
            </div>
            
            <div style="margin-top: 30px; color: #718096; font-size: 12px;">
                <p style="margin: 5px 0;">ID: CERT-' . $data['enrollment_id'] . '-' . date('Ymd') . '</p>
                <p style="margin: 5px 0;">Verifikasi: www.workyway.com/verify</p>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>';
        
        return $html;
    }
}