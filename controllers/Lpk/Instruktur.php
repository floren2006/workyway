<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instruktur extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        
        // Cek login dan role
        $this->_check_auth();
    }
    
    private function _check_auth()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        if ($this->session->userdata('role') != 'lpk') {
            $role = $this->session->userdata('role');
            if ($role == 'siswa') {
                redirect('siswa/dashboard');
            } elseif ($role == 'guru') {
                redirect('guru/dashboard');
            } else {
                redirect('login');
            }
        }
        
        $lpk_id = $this->session->userdata('lpk_id');
        if (!$lpk_id) {
            $user_id = $this->session->userdata('user_id');
            $this->db->where('user_id', $user_id);
            $lpk = $this->db->get('lpk')->row();
            
            if ($lpk) {
                $this->session->set_userdata('lpk_id', $lpk->lpk_id);
            } else {
                show_error('Akun LPK belum terkonfigurasi dengan benar.', 500);
            }
        }
    }
    
    // List semua instruktur berdasarkan LPK
   // Di method index() controller Instruktur, pastikan data kursus valid:
public function index()
{
    $lpk_id = $this->session->userdata('lpk_id');
    
    $this->db->where('lpk_id', $lpk_id);
    $data['instruktur'] = $this->db->get('instruktur')->result_array();

    $this->db->where('lpk_id', $lpk_id);
    $kursus_result = $this->db->get('kursus')->result_array();
    
    $data['kursus'] = is_array($kursus_result) ? $kursus_result : [];

    foreach ($data['instruktur'] as &$inst) {
        $this->db->where('instruktur_id', $inst['instruktur_id']);
        $this->db->where('lpk_id', $lpk_id);
        $kursus_count = $this->db->get('kursus')->num_rows();
        $inst['jumlah_kursus'] = $kursus_count;

        $this->db->select('COUNT(DISTINCT e.siswa_id) as total_siswa');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
        $this->db->where('k.instruktur_id', $inst['instruktur_id']);
        $this->db->where('k.lpk_id', $lpk_id);
        $query = $this->db->get()->row();
        $inst['jumlah_siswa'] = $query->total_siswa ? $query->total_siswa : 0;

        $this->db->where('instruktur_id', $inst['instruktur_id']);
        $this->db->where('lpk_id', $lpk_id);
        $inst['kursus_diajar'] = $this->db->get('kursus')->result_array();
    }

    $data['menu'] = 'instruktur';
    $this->load->view('lpk/instruktur_view', $data);
}

    // Tambah instruktur
    public function tambah()
    {
        $lpk_id = $this->session->userdata('lpk_id');

        if ($this->input->post()) {
            $data = [
                'lpk_id' => $lpk_id,
                'nama' => $this->input->post('nama'),
                'keahlian' => $this->input->post('keahlian'),
                'email' => $this->input->post('email'),
                'telp' => $this->input->post('telp'),
                'status' => $this->input->post('status'),
                'tanggal_dibuat' => date('Y-m-d H:i:s')
            ];

            // Upload foto jika ada
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './uploads/profilLPK/instruktur/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                }
            }

            $this->db->insert('instruktur', $data);
            $this->session->set_flashdata('success', 'Instruktur berhasil ditambahkan');
            redirect('lpk/instruktur');
        }

        $data['menu'] = 'instruktur';
        $this->load->view('lpk/instruktur_tambah', $data);
    }

    // Edit instruktur
    public function edit($id = null)
    {
        $lpk_id = $this->session->userdata('lpk_id');
        if (!$id) show_error('ID Instruktur tidak ditemukan');

        $this->db->where('instruktur_id', $id);
        $this->db->where('lpk_id', $lpk_id);
        $instruktur = $this->db->get('instruktur')->row_array();
        
        if (!$instruktur) show_error('Instruktur tidak ditemukan');

        if ($this->input->post()) {
            $data = [
                'nama' => $this->input->post('nama'),
                'keahlian' => $this->input->post('keahlian'),
                'email' => $this->input->post('email'),
                'telp' => $this->input->post('telp'),
                'status' => $this->input->post('status')
            ];

            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './uploads/profilLPK/instruktur/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto')) {
                    if (!empty($instruktur['foto']) && file_exists('./uploads/profilLPK/instruktur/'.$instruktur['foto'])) {
                        unlink('./uploads/profilLPK/instruktur/'.$instruktur['foto']);
                    }
                    
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                }
            }

            $this->db->where('instruktur_id', $id);
            $this->db->where('lpk_id', $lpk_id);
            $this->db->update('instruktur', $data);
            
            $this->session->set_flashdata('success', 'Instruktur berhasil diupdate');
            redirect('lpk/instruktur');
        }

        $data['instruktur'] = $instruktur;
        $data['menu'] = 'instruktur';
        $this->load->view('lpk/instruktur_edit_view', $data);
    }

    // Hapus instruktur
    public function hapus($id = null)
    {
        $lpk_id = $this->session->userdata('lpk_id');
        if (!$id) show_error('ID Instruktur tidak ditemukan');

        // Cek apakah instruktur punya kursus
        $this->db->where('guru_id', $id);
        $this->db->where('lpk_id', $lpk_id);
        $kursus = $this->db->get('kursus')->num_rows();
        
        if ($kursus > 0) {
            $this->session->set_flashdata('error', 'Instruktur tidak dapat dihapus karena masih memiliki kursus');
            redirect('lpk/instruktur');
        }

        // Ambil data untuk hapus foto
        $this->db->where('instruktur_id', $id);
        $this->db->where('lpk_id', $lpk_id);
        $instruktur = $this->db->get('instruktur')->row_array();
        
        if ($instruktur) {
            if (!empty($instruktur['foto']) && file_exists('./uploads/profilLPK/instruktur/'.$instruktur['foto'])) {
                unlink('./uploads/profilLPK/instruktur/'.$instruktur['foto']);
            }

            $this->db->where('instruktur_id', $id);
            $this->db->where('lpk_id', $lpk_id);
            $this->db->delete('instruktur');
            
            $this->session->set_flashdata('success', 'Instruktur berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Instruktur tidak ditemukan');
        }
        
        redirect('lpk/instruktur');
    }

    // Tugaskan instruktur ke kursus
    // Tugaskan instruktur ke kursus (LPK)
public function tugaskan()
{
    $lpk_id = $this->session->userdata('lpk_id');

    $instruktur_id = $this->input->post('instruktur_id');
    $kursus_id     = $this->input->post('kursus_id');

    // validasi kepemilikan
    $instruktur = $this->db
        ->where('instruktur_id', $instruktur_id)
        ->where('lpk_id', $lpk_id)
        ->get('instruktur')
        ->row();

    $kursus = $this->db
        ->where('kursus_id', $kursus_id)
        ->where('lpk_id', $lpk_id)
        ->get('kursus')
        ->row();

    if (!$instruktur || !$kursus) {
        $this->session->set_flashdata('error', 'Data tidak valid');
        redirect('lpk/instruktur');
    }

    // ✅ FIX UTAMA DI SINI
    $this->db->where('kursus_id', $kursus_id);
    $this->db->update('kursus', [
        'instruktur_id' => $instruktur_id,
        'guru_id'       => NULL
    ]);

    $this->session->set_flashdata('success', 'Instruktur berhasil ditugaskan');
    redirect('lpk/instruktur');
}

    
    // Lepas tugas instruktur dari kursus
    public function lepas_tugas($kursus_id = null)
    {
        $lpk_id = $this->session->userdata('lpk_id');
        if (!$kursus_id) show_error('ID Kursus tidak ditemukan');
        
        // Set guru_id menjadi NULL
        $this->db->where('kursus_id', $kursus_id);
        $this->db->where('lpk_id', $lpk_id);
        $this->db->update('kursus', ['guru_id' => NULL]);
        
        $this->session->set_flashdata('success', 'Tugas instruktur berhasil dilepas dari kursus');
        redirect('lpk/instruktur');
    }
}