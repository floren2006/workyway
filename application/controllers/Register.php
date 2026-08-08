<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
    }

    // ===================== PILIH ROLE =====================
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('landing');
        }

        $data = [
            'title' => 'Register | WorkyWay',
            'page'  => 'register'
        ];

        $this->load->view('header', $data);
        $this->load->view('register_view');
    }

    public function siswa()
    {
        $data = [
            'title'   => 'Register Siswa | WorkyWay',
            'page'    => 'register',
            'jurusan' => $this->db->get('jurusan')->result()
        ];

        $this->load->view('header', $data);
        $this->load->view('register_siswa', $data);
    }

    public function proses_siswa()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('telepon', 'Nomor Telepon', 'required|numeric|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'matches[password]');
        $this->form_validation->set_rules('jurusan_id', 'Jurusan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->siswa();
            return;
        }

        $this->db->trans_begin();

        // INSERT USERS
        $this->db->insert('users', [
            'nama'           => $this->input->post('nama', TRUE),
            'email'          => $this->input->post('email', TRUE),
            'password'       => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role'           => 'siswa',
            'telepon'    => $this->input->post('telepon'),
            'status_aktif'   => 1,
            'tanggal_daftar' => date('Y-m-d H:i:s')
        ]);

        $user_id = $this->db->insert_id();

        // INSERT SISWA
        $this->db->insert('siswa', [
            'user_id'    => $user_id,
            'jurusan_id' => $this->input->post('jurusan_id')
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            show_error('Registrasi gagal');
        }

        $this->db->trans_commit();

        $this->session->set_flashdata(
            'pesan_sukses',
            'Registrasi berhasil!.'
        );

        redirect('siswa/dashboard/index');
    }

    public function lpk()
    {
        $data = [
            'title' => 'Register LPK | WorkyWay',
            'page'  => 'register'
        ];
        $this->load->view('header', $data);
        $this->load->view('register_lpk');
    }
    public function proses_lpk()
{
    // ================= VALIDASI =================
    $this->form_validation->set_rules('nama_lembaga', 'Nama Lembaga', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'matches[password]');
    $this->form_validation->set_rules('telepon', 'Nomor Telepon', 'required|numeric|min_length[10]|max_length[15]');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required');

    if ($this->form_validation->run() === FALSE) {
        $this->lpk();
        return;
    }

    // ================= TRANSAKSI =================
    $this->db->trans_begin();

    // INSERT USERS
    $this->db->insert('users', [
        'nama'           => $this->input->post('nama_lembaga', TRUE),
        'email'          => $this->input->post('email', TRUE),
        'password'       => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'role'           => 'lpk',
        'telepon'        => $this->input->post('telepon', TRUE),
        'alamat'         => $this->input->post('alamat', TRUE),
        'status_aktif'   => 1,
        'tanggal_daftar' => date('Y-m-d H:i:s')
    ]);

    $user_id = $this->db->insert_id();

    // INSERT LPK
    $this->db->insert('lpk', [
        'user_id'       => $user_id,
        'nama_lembaga'  => $this->input->post('nama_lembaga', TRUE)
    ]);

    // ================= COMMIT / ROLLBACK =================
    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        show_error('Registrasi LPK gagal, silakan coba lagi.');
    }

    $this->db->trans_commit();

    // ================= FEEDBACK =================
    $this->session->set_flashdata(
        'pesan_sukses',
        'Registrasi LPK berhasil!.'
    );

    redirect('Lpk/Dashboard_lpk');
    }

    public function guru()
    {
            $data = [
                'title' => 'Register Guru | WorkyWay',
                'page'  => 'register'
            ];
            $this->load->view('header', $data);
            $this->load->view('register_guru');
    }
    public function proses_guru()
    {
    $this->form_validation->set_rules('nama', 'Nama', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('telepon', 'Telepon', 'required');
    $this->form_validation->set_rules('pengalaman', 'Pengalaman', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->guru();
        return;
    }

    // ================= UPLOAD PORTOFOLIO =================
    $config['upload_path']   = './uploads/portfolio/';
    $config['allowed_types'] = 'pdf|docx';
    $config['max_size']      = 2048;
    $config['file_name']     = 'portfolio_' . time();

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('portofolio')) {
        echo $this->upload->display_errors();
        die;
    }

    $upload_data = $this->upload->data();
    $nama_file   = $upload_data['file_name'];

    $this->db->trans_begin();

    // ================= INSERT USERS =================
    $this->db->insert('users', [
        'nama'           => $this->input->post('nama'),
        'email'          => $this->input->post('email'),
        'password'       => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'telepon'        => $this->input->post('telepon'),
        'role'           => 'guru',
        'status_aktif'   => 1,
        'tanggal_daftar' => date('Y-m-d H:i:s')
    ]);

    $user_id = $this->db->insert_id();

    // ================= INSERT GURU =================
    $this->db->insert('guru_freelance', [
        'user_id'           => $user_id,
        'keahlian'          => json_encode($this->input->post('keahlian')),
        'pengalaman'        => $this->input->post('pengalaman'),
        'portofolio'        => $nama_file,
        'status_verifikasi' => 'pending',
        'tanggal_daftar'    => date('Y-m-d H:i:s'),
        'rating_rata2'      => 0
    ]);

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo $this->db->error()['message'];
        die;
    }

    $this->db->trans_commit();

    // ================= FEEDBACK =================
    $this->session->set_flashdata(
        'pesan_sukses',
        'Registrasi LPK berhasil!.'
    );

    redirect('Lpk/Dashboard_guru');
    }
}
