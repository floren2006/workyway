<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct() {
       parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session'); 
        $this->load->model('guru/dashboard_model');
    }

    public function index() {
        $this->dashboard();
    }

        public function dashboard() {
        $guru_id = $this->session->userdata('guru_id');

        if (!$guru_id) {
            redirect('login');
        }

        $guru = $this->db->get_where('guru_freelance', [
            'guru_id' => $guru_id
        ])->row();

        if (!$guru) {
            show_error('Data guru tidak ditemukan');
        }
        // Ambil data guru dari database
        $guru_data = $this->dashboard_model->get_guru_by_id($guru_id);
        
        // Jika tidak ditemukan, berikan data default
        if (empty($guru_data)) {
            $guru_data = [
                'guru_id' => 1,
                'nama' => 'Prof. Dr. Agus Salim',
                'email' => 'agus@email.com',
                'foto_profil' => 'default.jpg',
                'rating_rata2' => 4.67
            ];
        }
        
        $data['guru'] = $guru_data;
        $data['title'] = 'Dashboard Guru';
        
        // Ambil data statistik menggunakan dashboard_model
        $statistik = $this->dashboard_model->get_statistik($guru_id);
        
        // Format data statistik sesuai dengan view
        $data['statistik'] = [
            'total_kursus' => $statistik['total_kursus'] ?? 0,
            'total_siswa' => $statistik['total_siswa'] ?? 0,
            'avg_rating' => $statistik['avg_rating'] ?? 0,
            'pendapatan' => $statistik['total_pendapatan'] ?? 0
        ];
        
        // Ambil kursus populer
        $data['kursus_populer'] = $this->dashboard_model->get_kursus_populer($guru_id, 5);
        
        // Ambil aktivitas
        $aktivitas_raw = $this->dashboard_model->get_aktivitas_terbaru($guru_id, 10);
        $data['aktivitas'] = $this->format_aktivitas($aktivitas_raw);
        
        // Load view
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/dashboard', $data);
        $this->load->view('templates/guru_footer');

    }

    private function format_aktivitas($aktivitas_raw) {
        $formatted = [];
        
        if (!empty($aktivitas_raw)) {
            foreach ($aktivitas_raw as $item) {
                $formatted[] = [
                    'deskripsi' => isset($item['pesan']) ? $item['pesan'] : 'Aktivitas baru',
                    'tanggal' => $this->format_tanggal($item['waktu'] ?? date('Y-m-d H:i:s'))
                ];
            }
        } else {
            // Jika tidak ada data aktivitas, berikan contoh
            $formatted = [
                [
                    'deskripsi' => 'Belum ada aktivitas terbaru',
                    'tanggal' => 'Baru saja'
                ]
            ];
        }
        
        return $formatted;
    }
    
    private function format_tanggal($date_string) {
        if (empty($date_string)) return 'Baru saja';
        
        try {
            $date = new DateTime($date_string);
            $now = new DateTime();
            $diff = $now->diff($date);
            
            if ($diff->days == 0) {
                if ($diff->h == 0) {
                    return $diff->i < 1 ? 'Baru saja' : $diff->i . ' menit lalu';
                }
                return $diff->h . ' jam lalu';
            } elseif ($diff->days == 1) {
                return 'Kemarin';
            } elseif ($diff->days < 7) {
                return $diff->days . ' hari lalu';
            }
            return date('d M Y', strtotime($date_string));
        } catch (Exception $e) {
            return 'Baru saja';
        }
    }
    
    public function refresh_data() {
        $guru_id = 1;
        $statistik = $this->dashboard_model->get_statistik($guru_id);
        
        $data = [
            'statistik' => [
                'total_kursus' => $statistik['total_kursus'] ?? 0,
                'total_siswa' => $statistik['total_siswa'] ?? 0,
                'avg_rating' => $statistik['avg_rating'] ?? 0,
                'pendapatan' => $statistik['total_pendapatan'] ?? 0,
                'pendapatan_formatted' => 'Rp ' . number_format($statistik['total_pendapatan'] ?? 0, 0, ',', '.')
            ],
            'timestamp' => date('d M Y H:i:s')
        ];
        
        echo json_encode($data);
    }
    
    public function logout() {
        redirect('dashboard');
    }
}