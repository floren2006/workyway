<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_lpk extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // LOAD LIBRARY
        $this->load->library('session');
        $this->load->database();

        // CEK LOGIN
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // CEK ROLE
        if ($this->session->userdata('role') !== 'lpk') {
            show_error('Akses ditolak', 403);
        }
    }

  public function index()
{
    $user_id = $this->session->userdata('user_id');
    $tahun = $this->input->get('tahun') ?? date('Y');

    // Ambil LPK
    $lpk = $this->db->get_where('lpk', [
        'user_id' => $user_id,
        'status_verifikasi' => 'approved'
    ])->row();

    if (!$lpk) {
        show_error('LPK belum diverifikasi admin');
    }

    $lpk_id = $lpk->lpk_id;

    // ================= STATISTIK =================

    // Kursus aktif
    $kursus_aktif = $this->db
        ->where('lpk_id', $lpk_id)
        ->where('status_kursus', 'aktif')
        ->count_all_results('kursus');

    // Total peserta
    $this->db->from('enrollment e');
    $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
    $this->db->where('k.lpk_id', $lpk_id);
    $total_peserta = $this->db->count_all_results();

    // Rating rata-rata
    $this->db->select_avg('e.rating');
    $this->db->from('enrollment e');
    $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
    $this->db->where('k.lpk_id', $lpk_id);
    $rating = (float) ($this->db->get()->row()->rating ?? 0);

    // Sertifikat
    $this->db->from('enrollment e');
    $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
    $this->db->where('k.lpk_id', $lpk_id);
    $this->db->where('e.sertifikat IS NOT NULL', null, false);
    $sertifikat = $this->db->count_all_results();

    // Review
    $this->db->from('enrollment e');
    $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
    $this->db->where('k.lpk_id', $lpk_id);
    $this->db->where('e.review IS NOT NULL', null, false);
    $review = $this->db->count_all_results();

    // Total pendaftaran (enrollment yang sudah bayar)
    $this->db->from('transaksi t');
    $this->db->join('enrollment e', 'e.enrollment_id = t.enrollment_id');
    $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
    $this->db->where('k.lpk_id', $lpk_id);
    $this->db->where('t.status', 'paid');

    $total_pendaftaran = $this->db->count_all_results();

    // ================= KURSUS POPULER =================

    $this->db->select('k.judul_kursus, COUNT(e.enrollment_id) AS total_peserta');
    $this->db->from('kursus k');
    $this->db->join('enrollment e', 'e.kursus_id = k.kursus_id');
    $this->db->join('transaksi t', 't.enrollment_id = e.enrollment_id');
    $this->db->where('k.lpk_id', $lpk_id);
    $this->db->where('k.status_kursus', 'aktif');
    $this->db->where('t.status', 'paid');
    $this->db->group_by('k.kursus_id');
    $this->db->order_by('total_peserta', 'DESC');
    $this->db->limit(5);
    $kursus_populer = $this->db->get()->result();

    // ================= CHART PENDAPATAN =================

   $tahun = $this->input->get('tahun') ?? date('Y');
    $this->db->select("
    MONTH(t.tanggal_transaksi) AS bulan,
    COUNT(t.transaksi_id) AS total
    ");
    $this->db->from('transaksi t');
    $this->db->join('enrollment e', 'e.enrollment_id = t.enrollment_id');
    $this->db->join('kursus k', 'k.kursus_id = e.kursus_id');
    $this->db->where('k.lpk_id', $lpk_id);
    $this->db->where('t.status', 'paid');
    $this->db->where('YEAR(t.tanggal_transaksi)', $tahun);
    $this->db->group_by('MONTH(t.tanggal_transaksi)');
    $this->db->order_by('bulan', 'ASC');

    $result = $this->db->get()->result();

    $bulan = [];
    $pendaftaran = [];

    for ($i = 1; $i <= 12; $i++) {
        $bulan[] = date('M', mktime(0, 0, 0, $i, 1));
        $pendaftaran[] = 0;
    }

    foreach ($result as $r) {
        $pendaftaran[$r->bulan - 1] = (int)$r->total;
    }

    // ================= KIRIM KE VIEW =================

   $data = [
    'kursus_aktif'      => $kursus_aktif,
    'total_peserta'    => $total_peserta,
    'rating'           => number_format($rating, 2),
    'sertifikat'       => $sertifikat,
    'review'           => $review,
    'total_pendaftaran'=> $total_pendaftaran,
    'kursus_populer'   => $kursus_populer,
    'bulan'            => json_encode($bulan),
    'pendaftaran'      => json_encode($pendaftaran),
    'tahun'            => $tahun,
    'lpk'              => $lpk
    ];

    $data['stat_pendaftaran'] = [];

    for ($i = 0; $i < 12; $i++) {
        $data['stat_pendaftaran'][] = [
            'bulan' => $bulan[$i],
            'total' => $pendaftaran[$i]
        ];
    }

    $this->load->view('lpk/dashboard_view', $data);

}

}