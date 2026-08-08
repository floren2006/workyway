<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_manajemen_laporan extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get overview statistics for the last 30 days
    public function get_overview_stats($days = 30) {
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime("-{$days} days"));
        
        return array(
            'pengguna_aktif' => $this->get_active_users($start_date, $end_date),
            'kursus_populer' => $this->get_popular_courses(),
            'pendapatan_total' => $this->get_total_revenue($start_date, $end_date),
            'rating_platform' => $this->get_platform_rating(),
            'pertumbuhan_pengguna' => $this->calculate_growth('users', $start_date, $end_date),
            'pertumbuhan_kursus' => $this->calculate_growth('kursus', $start_date, $end_date),
            'pertumbuhan_pendapatan' => $this->calculate_growth('revenue', $start_date, $end_date),
            'pertumbuhan_rating' => $this->calculate_rating_growth($start_date, $end_date)
        );
    }
    
    private function get_active_users($start_date, $end_date) {
        $this->db->where('tanggal_daftar >=', $start_date);
        $this->db->where('tanggal_daftar <=', $end_date);
        $this->db->where('status_aktif', 1);
        $this->db->where('role', 'siswa');
        return $this->db->count_all_results('users');
    }
    
    private function get_popular_courses() {
        $this->db->where('rating_rata2 >=', 4.0);
        $this->db->where('status_kursus', 'active');
        return $this->db->count_all_results('kursus');
    }
    
    private function get_total_revenue($start_date, $end_date) {
        $this->db->select_sum('t.jumlah');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('t.status', 'success');
        $this->db->where('t.tanggal_transaksi >=', $start_date);
        $this->db->where('t.tanggal_transaksi <=', $end_date);
        $result = $this->db->get()->row();
        return $result->jumlah ? $result->jumlah : 0;
    }
    
    private function get_platform_rating() {
        $this->db->select_avg('rating');
        $this->db->where('rating IS NOT NULL');
        $result = $this->db->get('enrollment')->row();
        return $result->rating ? round($result->rating, 1) : 0;
    }
    
    private function calculate_growth($type, $start_date, $end_date) {
        $previous_start = date('Y-m-d', strtotime($start_date . ' -30 days'));
        $previous_end = date('Y-m-d', strtotime($start_date . ' -1 day'));
        
        switch($type) {
            case 'users':
                $current = $this->get_active_users($start_date, $end_date);
                $previous = $this->get_active_users($previous_start, $previous_end);
                break;
            case 'kursus':
                $current = $this->get_popular_courses();
                $previous = $this->db->where('rating_rata2 >=', 4.0)
                                   ->where('status_kursus', 'active')
                                   ->count_all_results('kursus');
                break;
            case 'revenue':
                $current = $this->get_total_revenue($start_date, $end_date);
                $previous = $this->get_total_revenue($previous_start, $previous_end);
                break;
            default:
                $current = 0;
                $previous = 0;
        }
        
        if ($previous == 0) return "100%";
        $growth = (($current - $previous) / $previous) * 100;
        return round($growth, 0) . "%";
    }
    
    private function calculate_rating_growth($start_date, $end_date) {
        $previous_start = date('Y-m-d', strtotime($start_date . ' -30 days'));
        $previous_end = date('Y-m-d', strtotime($start_date . ' -1 day'));
        
        $this->db->select_avg('rating');
        $this->db->where('rating IS NOT NULL');
        $this->db->where('tanggal_daftar >=', $start_date);
        $this->db->where('tanggal_daftar <=', $end_date);
        $current_result = $this->db->get('enrollment')->row();
        $current = $current_result->rating ? round($current_result->rating, 1) : 0;
        
        $this->db->select_avg('rating');
        $this->db->where('rating IS NOT NULL');
        $this->db->where('tanggal_daftar >=', $previous_start);
        $this->db->where('tanggal_daftar <=', $previous_end);
        $previous_result = $this->db->get('enrollment')->row();
        $previous = $previous_result->rating ? round($previous_result->rating, 1) : 0;
        
        if ($previous == 0) return "+0.0";
        $growth = $current - $previous;
        return ($growth >= 0 ? "+" : "") . round($growth, 1);
    }
    
    public function get_monthly_growth() {
        $months = [];
        $current_date = date('Y-m-d');
        
        for ($i = 2; $i >= 0; $i--) {
            $month_start = date('Y-m-01', strtotime("-{$i} months", strtotime($current_date)));
            $month_end = date('Y-m-t', strtotime($month_start));
            $month_name = date('M', strtotime($month_start));
            
            $this->db->where('tanggal_daftar >=', $month_start);
            $this->db->where('tanggal_daftar <=', $month_end);
            $this->db->where('role', 'siswa');
            $users = $this->db->count_all_results('users');
            
            $this->db->where('tanggal_dibuat >=', $month_start);
            $this->db->where('tanggal_dibuat <=', $month_end);
            $courses = $this->db->count_all_results('kursus');
            
            $this->db->select_sum('t.jumlah');
            $this->db->from('transaksi t');
            $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
            $this->db->where('t.status', 'success');
            $this->db->where('t.tanggal_transaksi >=', $month_start);
            $this->db->where('t.tanggal_transaksi <=', $month_end);
            $revenue_result = $this->db->get()->row();
            $revenue = $revenue_result->jumlah ? $revenue_result->jumlah : 0;
            
            $months[] = array(
                'bulan' => $month_name,
                'users' => $users,
                'courses' => $courses,
                'revenue' => $revenue
            );
        }
        
        return $months;
    }
    
    public function get_top_instructors($limit = 3) {
        $this->db->select("
            u.nama as nama_instruktur,
            COUNT(DISTINCT k.kursus_id) as jumlah_kursus,
            COUNT(DISTINCT e.siswa_id) as jumlah_siswa,
            COALESCE(SUM(t.jumlah), 0) as total_pendapatan
        ");
        
        $this->db->from('kursus k');
        $this->db->join('guru_freelance g', 'k.guru_id = g.guru_id', 'left');
        $this->db->join('users u', 'g.user_id = u.user_id', 'left');
        $this->db->join('enrollment e', 'k.kursus_id = e.kursus_id', 'left');
        $this->db->join('transaksi t', 'e.enrollment_id = t.enrollment_id AND t.status = "success"', 'left'); // PERBAIKAN DI SINI
        $this->db->where('k.status_kursus', 'active');
        $this->db->where('k.guru_id IS NOT NULL');
        $this->db->group_by('g.guru_id, u.nama');
        $this->db->order_by('total_pendapatan', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    public function get_category_performance() {
        $this->db->select('
            kk.nama_kategori,
            COUNT(DISTINCT k.kursus_id) as jumlah_kursus,
            COUNT(DISTINCT e.siswa_id) as total_siswa,
            COALESCE(AVG(k.rating_rata2), 0) as rating_rata_rata
        ');
        
        $this->db->from('kategori_kursus kk');
        $this->db->join('kursus k', 'kk.kategori_id = k.kategori_id', 'left');
        $this->db->join('enrollment e', 'k.kursus_id = e.kursus_id', 'left');
        $this->db->where('k.status_kursus', 'active');
        $this->db->group_by('kk.kategori_id, kk.nama_kategori');
        $this->db->order_by('kk.nama_kategori', 'ASC');
        
        return $this->db->get()->result();
    }
    
    public function get_general_stats() {
        $stats = array();
        
        $this->db->where('role', 'siswa');
        $stats['total_siswa'] = $this->db->count_all_results('users');
        
        $this->db->where('role', 'guru');
        $stats['total_guru'] = $this->db->count_all_results('users');
        
        $this->db->where('role', 'lpk');
        $stats['total_lpk'] = $this->db->count_all_results('users');
        
        $this->db->where('status', 'success');
        $stats['total_transaksi'] = $this->db->count_all_results('transaksi');
        
        $this->db->where('status_kursus', 'active');
        $stats['kursus_aktif'] = $this->db->count_all_results('kursus');
        
        $this->db->where('status_kursus', 'pending');
        $stats['kursus_pending'] = $this->db->count_all_results('kursus');
        
        $stats['total_enrollment'] = $this->db->count_all_results('enrollment');
        
        $this->db->select_sum('jumlah');
        $this->db->where('status', 'success');
        $result = $this->db->get('transaksi')->row();
        $stats['total_pendapatan_all'] = $result->jumlah ? $result->jumlah : 0;
        
        return $stats;
    }
    
    public function generate_csv_report() {
        $filename = "laporan_" . date('Y-m-d_H-i-s') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, array('Laporan Statistik Platform - ' . date('d/m/Y H:i:s')));
        fputcsv($output, array(''));
        
        fputcsv($output, array('Statistik Overview (30 Hari Terakhir)'));
        $overview = $this->get_overview_stats();
        fputcsv($output, array('Pengguna Aktif', $overview['pengguna_aktif'] . ' (↑ ' . $overview['pertumbuhan_pengguna'] . ')'));
        fputcsv($output, array('Kursus Populer', $overview['kursus_populer'] . ' (↑ ' . $overview['pertumbuhan_kursus'] . ')'));
        fputcsv($output, array('Pendapatan Total', 'Rp ' . number_format($overview['pendapatan_total'], 0, ',', '.') . ' (↑ ' . $overview['pertumbuhan_pendapatan'] . ')'));
        fputcsv($output, array('Rating Platform', $overview['rating_platform'] . ' (↑ ' . $overview['pertumbuhan_rating'] . ')'));
        fputcsv($output, array(''));
        
        fputcsv($output, array('Pertumbuhan Bulanan'));
        fputcsv($output, array('Bulan', 'Users', 'Courses', 'Revenue'));
        $monthly = $this->get_monthly_growth();
        foreach ($monthly as $month) {
            fputcsv($output, array(
                $month['bulan'],
                $month['users'],
                $month['courses'],
                'Rp ' . number_format($month['revenue'], 0, ',', '.')
            ));
        }
        fputcsv($output, array(''));
        
        fputcsv($output, array('Top Instruktur'));
        fputcsv($output, array('Nama', 'Jumlah Kursus', 'Jumlah Siswa', 'Total Pendapatan'));
        $instructors = $this->get_top_instructors();
        foreach ($instructors as $inst) {
            fputcsv($output, array(
                $inst->nama_instruktur,
                $inst->jumlah_kursus,
                $inst->jumlah_siswa,
                'Rp ' . number_format($inst->total_pendapatan, 0, ',', '.')
            ));
        }
        fputcsv($output, array(''));
        
        fputcsv($output, array('Performa Kategori'));
        fputcsv($output, array('Kategori', 'Jumlah Kursus', 'Total Siswa', 'Rating Rata-rata'));
        $categories = $this->get_category_performance();
        foreach ($categories as $cat) {
            fputcsv($output, array(
                $cat->nama_kategori,
                $cat->jumlah_kursus,
                $cat->total_siswa,
                number_format($cat->rating_rata_rata, 1)
            ));
        }
        
        fclose($output);
        exit;
    }
}