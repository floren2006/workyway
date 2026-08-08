<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rating extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pendaftaran_model', 'enrollment');
        $this->load->model('Materi_model', 'materi');
        $this->load->model('Kursus_model', 'kursus');
        
        // Cek login
        if(!$this->session->userdata('siswa_id')) {
            redirect('login');
        }
    }

    /**
     * Submit rating dan review
     */
    public function submit() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('enrollment_id', 'Enrollment', 'required|numeric');
        $this->form_validation->set_rules('rating', 'Rating', 'required|numeric|greater_than[0]|less_than_equal_to[5]');
        $this->form_validation->set_rules('review', 'Review', 'trim|min_length[10]|max_length[500]');
        
        if($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }
        
        $enrollment_id = $this->input->post('enrollment_id');
        $rating = $this->input->post('rating');
        $review = $this->input->post('review');
        $allow_publish = $this->input->post('allow_publish') ? 1 : 0;
        
        // Get enrollment data
        $enrollment = $this->enrollment->get_enrollment_by_id($enrollment_id);
        
        // Cek apakah enrollment milik siswa yang login
        if($enrollment->siswa_id != $this->session->userdata('siswa_id')) {
            echo json_encode([
                'success' => false,
                'message' => 'Akses tidak diizinkan'
            ]);
            return;
        }
        
        // Cek apakah kursus sudah selesai
        $is_completed = $this->enrollment->is_course_completed(
            $enrollment->kursus_id, 
            $this->session->userdata('siswa_id')
        );
        
        if(!$is_completed) {
            echo json_encode([
                'success' => false,
                'message' => 'Anda harus menyelesaikan semua materi terlebih dahulu'
            ]);
            return;
        }
        
        // Update rating dan review
        $result = $this->enrollment->update_rating_review(
            $enrollment_id, 
            $rating, 
            $review, 
            $allow_publish
        );
        
        if($result) {
            // Tambahkan notifikasi untuk admin/instructor
            $this->enrollment->insert_notifikasi(
                $this->session->userdata('user_id'),
                'Rating Baru',
                'Siswa memberikan rating untuk kursus'
            );
            
            echo json_encode([
                'success' => true,
                'message' => 'Rating dan review berhasil disimpan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan rating dan review'
            ]);
        }
    }

    /**
     * Get rating form
     */
    public function get_form($enrollment_id) {
        $enrollment = $this->enrollment->get_enrollment_with_rating($enrollment_id);
        
        // Cek apakah enrollment milik siswa yang login
        if($enrollment->siswa_id != $this->session->userdata('siswa_id')) {
            show_404();
        }
        
        $data['enrollment'] = $enrollment;
        $this->load->view('siswa/rating/rating_form', $data);
    }

    /**
     * Get reviews for a course
     */
    public function get_reviews($kursus_id) {
        $page = $this->input->get('page') ?: 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;
        
        $reviews = $this->enrollment->get_all_reviews($kursus_id, $limit, $offset);
        $rating_stats = $this->enrollment->get_rating_stats($kursus_id);
        
        echo json_encode([
            'success' => true,
            'reviews' => $reviews,
            'stats' => $rating_stats,
            'has_more' => count($reviews) == $limit
        ]);
    }

    /**
     * Check if student can rate a course
     */
    public function can_rate($kursus_id) {
        $siswa_id = $this->session->userdata('siswa_id');
        
        // Get enrollment
        $enrollment = $this->enrollment->get_enrollment_by_kursus_siswa($kursus_id, $siswa_id);
        
        if(!$enrollment) {
            echo json_encode([
                'success' => false,
                'message' => 'Anda tidak terdaftar dalam kursus ini'
            ]);
            return;
        }
        
        // Check if already rated
        if($enrollment->rating) {
            echo json_encode([
                'success' => true,
                'can_rate' => false,
                'has_rated' => true,
                'rating' => $enrollment->rating,
                'review' => $enrollment->review
            ]);
            return;
        }
        
        // Check if course completed
        $is_completed = $this->enrollment->is_course_completed($kursus_id, $siswa_id);
        
        echo json_encode([
            'success' => true,
            'can_rate' => $is_completed,
            'has_rated' => false,
            'is_completed' => $is_completed,
            'enrollment_id' => $enrollment->enrollment_id
        ]);
    }
}