<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kursus extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // List kursus LPK
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('login');

        // Ambil LPK yang login
        $lpk = $this->db->where('user_id', $user_id)->get('lpk')->row_array();
        if (!$lpk) show_error('LPK tidak ditemukan');

        // Ambil kursus LPK
        $this->db->select('k.*, 
            (SELECT COUNT(*) FROM enrollment e WHERE e.kursus_id = k.kursus_id) AS peserta
        ');
        $this->db->from('kursus k');
        $this->db->where('k.lpk_id', $lpk['lpk_id']);
        $data['kursus'] = $this->db->get()->result_array();
        $data['menu'] = 'kursus';

        $this->load->view('lpk/kursus_view', $data);
    }

    // Tambah kursus
    public function tambah()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('login');

        // Ambil LPK yang login
        $lpk = $this->db->where('user_id', $user_id)->get('lpk')->row_array();
        if (!$lpk) show_error('LPK tidak ditemukan');

        // Ambil guru freelance yang terkait LPK ini (lpk_id), tampilkan nama dari users
        $this->db->select('g.guru_id, u.nama');
        $this->db->from('guru_freelance g');
        $this->db->join('users u', 'u.user_id = g.user_id');
        $this->db->where('g.lpk_id', $lpk['lpk_id']);
        $this->db->where('u.role', 'guru');
        $data['guru'] = $this->db->get()->result_array();

        // Ambil kategori kursus
        $data['kategori'] = $this->db->get('kategori_kursus')->result_array();

        // Proses form submit
        if ($this->input->post()) {
            $input = $this->input->post();

            $kursus_data = [
                'kategori_id'    => $input['kategori_id'],
                'lpk_id'         => $lpk['lpk_id'],         // LPK yang login
                'guru_id'        => $input['guru_id'],      // HARUS guru_id dari guru_freelance
                'judul_kursus'   => $input['judul_kursus'],
                'deskripsi'      => $input['deskripsi'],
                'detail'         => $input['detail'],
                'biaya'          => $input['biaya'],
                'durasi'         => $input['durasi'],
                'jadwal_mulai'   => $input['jadwal_mulai'],
                'jadwal_selesai' => $input['jadwal_selesai'],
                'status_kursus'  => $input['status_kursus'],
                'rating_rata2'   => 0,
                'tanggal_dibuat' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('kursus', $kursus_data);
            $this->session->set_flashdata('success', 'Kursus berhasil ditambahkan');
            redirect('lpk/kursus');
        }

        $this->load->view('lpk/tambah_kursus_view', $data);
    }

    // Edit kursus
    public function edit($kursus_id = null)
    {
        if (!$this->session->userdata('user_id')) redirect('login');
        if (!$kursus_id) show_error('ID Kursus tidak ditemukan');

        $kursus = $this->db->get_where('kursus', ['kursus_id' => $kursus_id])->row_array();
        if (!$kursus) show_error('Kursus tidak ditemukan');

        // Ambil LPK yang login
        $lpk = $this->db->where('user_id', $this->session->userdata('user_id'))->get('lpk')->row_array();

        // Ambil kategori kursus
        $data['kategori'] = $this->db->get('kategori_kursus')->result_array();

        // Ambil guru sesuai LPK
        $this->db->select('g.guru_id, u.nama');
        $this->db->from('guru_freelance g');
        $this->db->join('users u', 'u.user_id = g.user_id');
        $this->db->where('g.lpk_id', $lpk['lpk_id']);
        $this->db->where('u.role', 'guru');
        $data['guru'] = $this->db->get()->result_array();


        if ($this->input->post()) {
            $input = $this->input->post();

            $kursus_data = [
                'kategori_id'    => $input['kategori_id'],
                'guru_id'        => $input['guru_id'],
                'judul_kursus'   => $input['judul_kursus'],
                'deskripsi'      => $input['deskripsi'],
                'detail'         => $input['detail'],
                'biaya'          => $input['biaya'],
                'durasi'         => $input['durasi'],
                'jadwal_mulai'   => $input['jadwal_mulai'],
                'jadwal_selesai' => $input['jadwal_selesai'],
                'status_kursus'  => $input['status_kursus']
            ];

            $this->db->where('kursus_id', $kursus_id);
            echo '<pre>';
            $this->db->update('kursus', $kursus_data);

            $this->session->set_flashdata('success','Kursus berhasil diupdate');
            redirect('lpk/kursus');
        }

        $data['kursus'] = $kursus;
        $this->load->view('lpk/kursus_edit_view', $data);
    }

    // Hapus kursus
   public function hapus($kursus_id = null)
    {
        if (!$this->session->userdata('user_id')) redirect('login');
        if (!$kursus_id) show_error('ID Kursus tidak ditemukan');

        //  CEK APAKAH ADA PESERTA
        $jumlah_peserta = $this->db
            ->where('kursus_id', $kursus_id)
            ->count_all_results('enrollment');

        if ($jumlah_peserta > 0) {
            // TOLAK HAPUS
            $this->session->set_flashdata(
                'error',
                'Kursus tidak dapat dihapus karena sudah memiliki peserta.'
            );
            redirect('lpk/kursus');
            return;
        }

        // HAPUS DATA TERKAIT
        $this->db->where('kursus_id', $kursus_id)->delete('materi_kursus');
        $this->db->where('kursus_id', $kursus_id)->delete('kursus');

        // PESAN SUKSES
        $this->session->set_flashdata(
            'success',
            'Kursus berhasil dihapus.'
        );
        redirect('lpk/kursus');
    }


}
