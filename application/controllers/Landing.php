<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
        $this->load->model('Landing_model');
    }

    public function index()
    {
        try {
            // Check if user is logged in
            $user_id = $this->session->userdata('user_id');
            $is_logged_in = $this->session->userdata('logged_in');
            
            // Prepare data untuk view
            $data = array(
                'title' => 'Platform Kursus Online untuk SMK',
                'description' => 'Belajar skill digital dengan kursus berkualitas dari LPK dan guru freelance terverifikasi',
                'page' => 'landing',
                
                // Stats dari database
                'stats' => $this->_get_stats(),
                
                // Data untuk konten
                'popular_courses' => $this->Landing_model->get_popular_kursus_by_enrollment(4),
                'testimonials' => $this->Landing_model->get_testimonials(3),
                'all_instructors' => $this->Landing_model->get_all_instructors(8),
                'lpk_verified' => $this->Landing_model->get_verified_lpk(3),
                
                // Data untuk rekomendasi
                'is_logged_in' => $is_logged_in,
                'recommendation_type' => $is_logged_in ? 'personalized' : 'popular'
            );
            $this->load->view('header', $data);
            $this->load->view('landing_view', $data);
            $this->load->view('footer', $data);
            
        } catch (Exception $e) {
            $this->_show_error_page($e);
        }
    }
    
    private function _get_stats()
    {
        try {
            return array(
                'total_courses' => $this->Landing_model->count_total_courses(),
                'total_students' => $this->Landing_model->count_total_students(),
                'total_instructors' => $this->Landing_model->count_total_instructors(),
                'total_lpk' => $this->Landing_model->count_total_lpk(),
                'satisfaction_rate' => 95
            );
        } catch (Exception $e) {
            return array(
                'total_courses' => 16,
                'total_students' => 10,
                'total_instructors' => 5,
                'total_lpk' => 3,
                'satisfaction_rate' => 95
            );
        }
    }
    
    private function _show_error_page($error)
    {
        $data = array(
            'title' => 'Error - WorkyWay',
            'page' => 'error',
            'error' => $error
        );
        
        $this->load->view('header', $data);
        echo '<div class="container mt-5">
                <div class="alert alert-danger">
                    <h4>Terjadi Kesalahan</h4>
                    <p>Mohon maaf, sedang ada masalah teknis. Silakan coba beberapa saat lagi.</p>
                    <p><small>Error: ' . $error->getMessage() . '</small></p>
                </div>
                <a href="' . base_url('landing') . '" class="btn btn-primary">Kembali ke Home</a>
              </div>';
        $this->load->view('footer');
    }

    /**
     * Halaman Semua Kursus
     */
    public function courses()
    {
        $kategori_id = $this->input->get('category');
        
        if ($kategori_id) {
            $courses = $this->Landing_model->get_courses_by_category($kategori_id, 12);
        } else {
            $this->db->select('k.*, kk.nama_kategori');
            $this->db->from('kursus k');
            $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
            $this->db->where('k.status_kursus', 'active');
            $this->db->order_by('k.rating_rata2', 'DESC');
            $this->db->limit(12);
            $courses = $this->db->get()->result();
        }
        
        $data = array(
            'title' => 'Semua Kursus | WorkyWay',
            'description' => 'Pilih dari berbagai kursus berkualitas',
            'page' => 'courses',
            'courses' => $courses,
            'categories' => $this->Landing_model->get_categories()
        );
        
        $this->load->view('header', $data);
        $this->load->view('course', $data);
        $this->load->view('footer', $data);
    }

    /**
     * Detail Kursus
     */
    public function course_detail($id)
    {
        $this->db->where('kursus_id', $id);
        $this->db->where('status_kursus', 'active');
        $course = $this->db->get('kursus')->row();
        
        if (!$course) {
            show_404();
        }
        
        // Get kategori
        $this->db->where('kategori_id', $course->kategori_id);
        $kategori = $this->db->get('kategori_kursus')->row();
        
        // Get instructor info
        $instructor_name = 'Instruktur Tidak Tersedia';
        if ($course->lpk_id) {
            $this->db->select('l.nama_lembaga, u.foto_profil');
            $this->db->from('lpk l');
            $this->db->join('users u', 'l.user_id = u.user_id');
            $this->db->where('l.lpk_id', $course->lpk_id);
            $instructor = $this->db->get()->row();
            $instructor_name = $instructor ? $instructor->nama_lembaga : 'LPK Tidak Ditemukan';
        } elseif ($course->guru_id) {
            $this->db->select('u.nama, u.foto_profil');
            $this->db->from('guru_freelance g');
            $this->db->join('users u', 'g.user_id = u.user_id');
            $this->db->where('g.guru_id', $course->guru_id);
            $instructor = $this->db->get()->row();
            $instructor_name = $instructor ? $instructor->nama : 'Guru Tidak Ditemukan';
        }
        
        $data = array(
            'title' => $course->judul_kursus . ' | WorkyWay',
            'description' => $course->deskripsi,
            'page' => 'course_detail',
            'course' => $course,
            'kategori' => $kategori,
            'instructor_name' => $instructor_name,
            'materials' => $this->db->where('kursus_id', $id)->get('materi_kursus')->result()
        );
        
        $this->load->view('header', $data);
        $this->load->view('course_detail', $data);
        $this->load->view('footer', $data);
    }
    
    
    /**
     * Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('landing');
    }

    /**
     * Simple Test Page
     */
    public function test()
    {
        $data = array(
            'title' => 'Test Page | WorkyWay',
            'description' => 'Test database connection',
            'page' => 'test'
        );
        
        echo "<h1>Test Page</h1>";
        echo "<p>Controller bekerja dengan baik!</p>";
        echo "<a href='" . base_url('landing') . "'>Kembali ke Home</a>";
    }

    /**
     * Test Database Connection
     */
    public function test_db()
    {
        $data = array(
            'title' => 'Test Database | WorkyWay',
            'description' => 'Test koneksi database',
            'page' => 'test_db'
        );
        
        $this->load->view('header', $data);
        
        echo "<div class='container mt-5'>";
        echo "<h1>Database Connection Test</h1>";
        
        if (!$this->db->conn_id) {
            echo "<p style='color: red;'>✗ Database Not Connected</p>";
        } else {
            echo "<p style='color: green;'>✓ Database Connected</p>";
            
            // Test tables
            $tables = ['users', 'kursus', 'kategori_kursus', 'siswa', 'guru_freelance', 'lpk'];
            echo "<h3>Table Counts:</h3>";
            echo "<ul>";
            foreach ($tables as $table) {
                $count = $this->db->count_all($table);
                echo "<li>$table: $count records</li>";
            }
            echo "</ul>";
        }
        
        echo "<a href='" . base_url('landing') . "' class='btn btn-primary mt-3'>Kembali ke Home</a>";
        echo "</div>";
        
        $this->load->view('footer', $data);
    }
}