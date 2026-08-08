<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_lpk extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    /* ================= PROFIL VIEW ================= */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('login');

        $this->db->select('
            l.lpk_id,
            l.nama_lembaga,
            l.deskripsi,
            l.nomor_izin,
            l.akreditasi,
            l.tahun_berdiri,
            l.status_verifikasi,
            u.email,
            u.telepon,
            u.alamat,
            u.foto_profil
        ');
        $this->db->from('lpk l');
        $this->db->join('users u', 'u.user_id = l.user_id');
        $this->db->where('l.user_id', $user_id);

        $lpk = $this->db->get()->row_array();
        if (!$lpk) show_error('Data LPK tidak ditemukan');

        $jumlah_instruktur = $this->db
            ->where('lpk_id', $lpk['lpk_id'])
            ->where('status', 'aktif')
            ->count_all_results('instruktur');

        $data['menu'] = 'profil';
        $data['profil'] = [
            'nama_lembaga' => $lpk['nama_lembaga'],
            'deskripsi'    => $lpk['deskripsi'],
            'email'        => $lpk['email'],
            'telepon'      => $lpk['telepon'],
            'alamat'       => $lpk['alamat'],
            'izin'         => $lpk['nomor_izin'],
            'tahun'        => $lpk['tahun_berdiri'],
            'status'       => ucfirst($lpk['status_verifikasi']),
            'foto'         => $lpk['foto_profil'],
            'akreditasi'   => $lpk['akreditasi'],
            'instruktur'   => $jumlah_instruktur
        ];

        $this->load->view('lpk/profil_view', $data);
    }

    /* ================= EDIT PROFIL ================= */
    public function edit()
{
    $user_id = $this->session->userdata('user_id');
    if (!$user_id) redirect('login');

    /* ================= SUBMIT ================= */
    if ($this->input->method() === 'post') {

        $lpk_id = $this->input->post('lpk_id');

        /* ================= USERS ================= */
        $data_user = [];

        if ($this->input->post('email')) {
            $data_user['email'] = $this->input->post('email', true);
        }

        if ($this->input->post('telepon')) {
            $data_user['telepon'] = $this->input->post('telepon', true);
        }

        if ($this->input->post('alamat')) {
            $data_user['alamat'] = $this->input->post('alamat', true);
        }

        /* ===== FOTO PROFIL (USERS) ===== */
        if (!empty($_FILES['foto_profil']['name'])) {

            $config = [
                'upload_path'   => FCPATH.'uploads/profilLPK/',
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 2048,
                'file_name'     => 'lpk_'.$user_id.'_'.time(),
                'overwrite'     => true
            ];

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto_profil')) {
                $data_user['foto_profil'] = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata(
                    'pesan_gagal',
                    strip_tags($this->upload->display_errors())
                );
                redirect('lpk/profil_lpk/edit');
            }
        }

        if (!empty($data_user)) {
            $this->db->where('user_id', $user_id)->update('users', $data_user);
        }

        /* ================= LPK ================= */
        $data_lpk = [];

        if ($this->input->post('nama_lembaga')) {
            $data_lpk['nama_lembaga'] = $this->input->post('nama_lembaga', true);
        }

        if ($this->input->post('deskripsi')) {
            $data_lpk['deskripsi'] = $this->input->post('deskripsi', true);
        }

        if ($this->input->post('nomor_izin')) {
            $data_lpk['nomor_izin'] = $this->input->post('nomor_izin', true);
        }

        if ($this->input->post('akreditasi')) {
            $data_lpk['akreditasi'] = $this->input->post('akreditasi', true);
        }

        if ($this->input->post('tahun_berdiri')) {
            $data_lpk['tahun_berdiri'] = $this->input->post('tahun_berdiri', true);
        }

        if (!empty($data_lpk)) {
            $this->db->where('lpk_id', $lpk_id)->update('lpk', $data_lpk);
        }

        $this->session->set_flashdata('pesan_sukses', 'Profil berhasil diperbarui');
        redirect('lpk/profil_lpk');
    }

    /* ================= VIEW EDIT ================= */
    $this->db->select('
        l.lpk_id,
        l.nama_lembaga,
        l.deskripsi,
        l.nomor_izin,
        l.akreditasi,
        l.tahun_berdiri,
        u.email,
        u.telepon,
        u.alamat,
        u.foto_profil
    ');
    $this->db->from('lpk l');
    $this->db->join('users u', 'u.user_id = l.user_id');
    $this->db->where('l.user_id', $user_id);

    $row = $this->db->get()->row_array();

    $data['menu'] = 'profil';
    $data['profil'] = [
        'lpk_id'        => $row['lpk_id'],
        'nama_lembaga'  => $row['nama_lembaga'] ?? '',
        'deskripsi'     => $row['deskripsi'] ?? '',
        'email'         => $row['email'] ?? '',
        'telepon'       => $row['telepon'] ?? '',
        'alamat'        => $row['alamat'] ?? '',
        'izin'          => $row['nomor_izin'] ?? '',
        'akreditasi'    => $row['akreditasi'] ?? '',
        'tahun'         => $row['tahun_berdiri'] ?? '',
        'foto'          => $row['foto_profil'] ?? null
    ];

    $this->load->view('lpk/editprofil_view', $data);
}

}

