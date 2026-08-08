<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_manajemen_transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load required libraries
        $this->load->library('session'); // Load session library
        $this->load->model('admin/admin_transaksi_model');
        $this->load->library('pagination');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            // Auto set session untuk admin ID 1 (hanya untuk development/testing)
            $this->session->set_userdata(array(
                'logged_in' => TRUE,
                'user_id' => 1,
                'nama' => 'Admin Utama',
                'email' => 'admin@kursusonline.com',
                'role' => 'admin'
            ));
        }
        
        // Verify user role (optional, tapi baik untuk security)
        if ($this->session->userdata('role') !== 'admin') {
            redirect('login'); // Redirect ke halaman login jika bukan admin
        }
    }

    public function index() {
        // Check session
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        $search = $this->input->get('search');
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get transaction statistics
        $data['stats'] = $this->admin_transaksi_model->get_total_transaksi_stats();
        
        // Get all transactions
        $data['transactions'] = $this->admin_transaksi_model->get_all_transaksi($limit, $offset, $search);
        
        // Configure pagination
        $config['base_url'] = base_url('admin/manajemen_transaksi');
        $config['total_rows'] = $this->admin_transaksi_model->count_all_transaksi($search);
        $config['per_page'] = $limit;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        
        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($config['total_rows'] / $limit);
        $data['search'] = $search;
        
        // Load view
        $this->load->view('admin/admin_transaksi_view', $data);
    }

    public function update_status() {
        // Check session
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        if ($this->input->post()) {
            $id = $this->input->post('transaksi_id');
            $status = $this->input->post('status');
            
            $result = $this->admin_transaksi_model->update_status($id, $status);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Status transaksi berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate status transaksi!');
            }
            
            redirect('admin/manajemen_transaksi');
        }
    }

    public function export_excel() {
        // Check session
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        $transactions = $this->admin_transaksi_model->get_all_transaksi(1000, 0, '');
        
        // Create CSV content
        $csv = "ID Transaksi,Siswa,Kursus,Total,Komisi Platform,Tanggal,Status,Metode Pembayaran\n";
        
        foreach ($transactions as $transaction) {
            // Format status
            $status = '';
            switch($transaction->status) {
                case 'success': $status = 'Sukses'; break;
                case 'pending': $status = 'Pending'; break;
                case 'failed': $status = 'Gagal'; break;
                default: $status = $transaction->status;
            }
            
            $csv .= 'TRX' . str_pad($transaction->transaksi_id, 3, '0', STR_PAD_LEFT) . ',';
            $csv .= '"' . $transaction->siswa_nama . '",';
            $csv .= '"' . $transaction->judul_kursus . '",';
            $csv .= '"Rp ' . number_format($transaction->jumlah, 0, ',', '.') . '",';
            $csv .= '"Rp ' . number_format($transaction->komisi_platform, 0, ',', '.') . '",';
            $csv .= date('d-m-Y H:i', strtotime($transaction->tanggal_transaksi)) . ',';
            $csv .= $status . ',';
            $csv .= $transaction->metode_pembayaran . "\n";
        }
        
        // Set filename and headers
        $filename = 'transaksi_' . date('Ymd_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        echo $csv;
        exit;
    }
}
?>