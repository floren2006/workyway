<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('lpk/Siswa_model');
    }

    public function index()
    {
        // 1. CEK LOGIN
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        // 2. AMBIL DATA LPK DARI USER LOGIN
        $lpk = $this->db->get_where('lpk', [
            'user_id' => $this->session->userdata('user_id')
        ])->row_array();

        // 3. AMBIL FILTER DARI URL (GET)
        $kursus_id     = $this->input->get('kursus_id');
        $instruktur_id = $this->input->get('instruktur_id');

        // 4. AMBIL DATA SISWA (PAKAI FILTER)
        $data['menu'] = 'siswa';
        $data['siswa'] = $this->Siswa_model->getByLpk(
            $lpk['lpk_id'],
            $kursus_id,
            $instruktur_id
        );

        // 5. DATA UNTUK DROPDOWN FILTER
        $data['kursus'] = $this->db
            ->where('lpk_id', $lpk['lpk_id'])
            ->get('kursus')
            ->result_array();

        $data['instruktur'] = $this->db
            ->where('lpk_id', $lpk['lpk_id'])
            ->get('instruktur')
            ->result_array();

        // 6. LOAD VIEW
        $this->load->view('lpk/sidebar');
        $this->load->view('lpk/siswa_view', $data);
    }

    public function submit_tugas()
{
    $siswa_id = $this->session->userdata('siswa_id');
    $tugas_id = $this->input->post('tugas_id');

    // validasi sederhana
    if (!$siswa_id || !$tugas_id) {
        show_error('Akses tidak valid');
    }

    // CEK: jangan submit 2x
    $cek = $this->db->get_where('pengumpulan_tugas', [
        'siswa_id' => $siswa_id,
        'tugas_id' => $tugas_id
    ])->row();

    if ($cek) {
        $this->session->set_flashdata('error', 'Tugas sudah dikumpulkan');
        redirect('siswa/tugas');
    }

    // INSERT pengumpulan
    $this->db->insert('pengumpulan_tugas', [
        'tugas_id'        => $tugas_id,
        'siswa_id'        => $siswa_id,
        'tanggal_kumpul'  => date('Y-m-d H:i:s'),
        'status'          => 'dikumpulkan'
    ]);

    // === NOTIFIKASI KE LPK ===
    $lpk = $this->db
        ->select('k.lpk_id, l.user_id')
        ->from('tugas t')
        ->join('materi_kursus m', 't.materi_id = m.materi_id')
        ->join('kursus k', 'm.kursus_id = k.kursus_id')
        ->join('lpk l', 'k.lpk_id = l.lpk_id')
        ->where('t.tugas_id', $tugas_id)
        ->get()->row();

    if ($lpk) {
        $this->db->insert('notifikasi', [
            'penerima_id' => $lpk->user_id,
            'judul'       => 'Tugas Baru Dikumpulkan',
            'isi'         => 'Ada tugas baru yang perlu dinilai',
            'tanggal'     => date('Y-m-d H:i:s'),
            'status'      => 'unread'
        ]);
    }

    $this->session->set_flashdata('success', 'Tugas berhasil dikumpulkan');
    redirect('siswa/tugas');
}


}
