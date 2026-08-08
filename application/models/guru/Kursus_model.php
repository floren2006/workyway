<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kursus_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Ambil kursus berdasarkan guru_id
     */
    public function get_kursus_by_guru($guru_id, $limit = null) {
        // Validasi input
        if (!$guru_id || !is_numeric($guru_id)) {
            return [];
        }
        
        $this->db->select('k.*, kk.nama_kategori, 
                          (SELECT COUNT(DISTINCT e.siswa_id) 
                           FROM enrollment e 
                           WHERE e.kursus_id = k.kursus_id) as jumlah_siswa,
                          (SELECT COUNT(DISTINCT f.forum_id) 
                           FROM forum f 
                           WHERE f.kursus_id = k.kursus_id) as jumlah_forum');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id', 'left');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->order_by('k.tanggal_dibuat', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $result = $this->db->get()->result_array();
        
        // Tambahkan URL gambar dan format data
        foreach ($result as &$row) {
            $row['gambar_url'] = $this->get_gambar_url($row['gambar_kursus']);
            $row['biaya_formatted'] = 'Rp ' . number_format($row['biaya'], 0, ',', '.');
            $row['rating_display'] = $row['rating_rata2'] > 0 ? number_format($row['rating_rata2'], 1) : 'Belum ada rating';
            $row['tanggal_format'] = $this->format_tanggal($row['tanggal_dibuat']);
            
            // Status badge
            $row['status_badge'] = $this->get_status_badge($row['status_kursus']);
        }
        
        return $result;
    }

    /**
     * Ambil statistik kursus untuk guru
     */
    public function get_statistik_kursus($guru_id) {
        if (!$guru_id) {
            return [];
        }
        
        $statistik = [
            'total_kursus' => 0,
            'kursus_aktif' => 0,
            'kursus_nonaktif' => 0,
            'kursus_pending' => 0,
            'total_siswa' => 0,
            'total_pendapatan' => 0,
            'rating_tertinggi' => 0,
            'kursus_rating_tertinggi' => '-',
            'kursus_populer' => '-',
            'jumlah_siswa_populer' => 0
        ];
        
        // Total kursus
        $statistik['total_kursus'] = $this->count_kursus_by_guru($guru_id);
        
        // Kursus aktif
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'active');
        $statistik['kursus_aktif'] = $this->db->count_all_results('kursus');
        
        // Kursus tidak aktif
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'inactive');
        $statistik['kursus_nonaktif'] = $this->db->count_all_results('kursus');
        
        // Kursus pending
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'pending');
        $statistik['kursus_pending'] = $this->db->count_all_results('kursus');
        
        // Total siswa di semua kursus
        $this->db->select('COUNT(DISTINCT e.siswa_id) as total_siswa');
        $this->db->from('enrollment e');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $result = $this->db->get()->row_array();
        $statistik['total_siswa'] = $result['total_siswa'] ?? 0;
        
        // Total pendapatan
        $this->db->select('SUM(t.gaji) as total_pendapatan');
        $this->db->from('transaksi t');
        $this->db->join('enrollment e', 't.enrollment_id = e.enrollment_id');
        $this->db->join('kursus k', 'e.kursus_id = k.kursus_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->where('t.status', 'success');
        $result = $this->db->get()->row_array();
        $statistik['total_pendapatan'] = $result['total_pendapatan'] ?? 0;
        
        // Kursus dengan rating tertinggi
        $this->db->select('judul_kursus, rating_rata2');
        $this->db->where('guru_id', $guru_id);
        $this->db->where('rating_rata2 >', 0);
        $this->db->order_by('rating_rata2', 'DESC');
        $this->db->limit(1);
        $rating = $this->db->get('kursus')->row_array();
        if ($rating) {
            $statistik['rating_tertinggi'] = $rating['rating_rata2'];
            $statistik['kursus_rating_tertinggi'] = $rating['judul_kursus'];
        }
        
        // Kursus dengan siswa terbanyak
        $this->db->select('k.judul_kursus, COUNT(DISTINCT e.siswa_id) as total_siswa');
        $this->db->from('kursus k');
        $this->db->join('enrollment e', 'k.kursus_id = e.kursus_id', 'left');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->group_by('k.kursus_id');
        $this->db->order_by('total_siswa', 'DESC');
        $this->db->limit(1);
        $populer = $this->db->get()->row_array();
        if ($populer) {
            $statistik['kursus_populer'] = $populer['judul_kursus'];
            $statistik['jumlah_siswa_populer'] = $populer['total_siswa'];
        }
        
        // Format number
        $statistik['total_pendapatan_formatted'] = 'Rp ' . number_format($statistik['total_pendapatan'], 0, ',', '.');
        $statistik['rating_tertinggi_formatted'] = number_format($statistik['rating_tertinggi'], 1);
        
        return $statistik;
    }

    /**
     * Ambil kursus aktif saja
     */
    public function get_kursus_aktif_by_guru($guru_id, $limit = null) {
        $this->db->where('guru_id', $guru_id);
        $this->db->where('status_kursus', 'active');
        $this->db->order_by('tanggal_dibuat', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $result = $this->db->get('kursus')->result_array();
        
        foreach ($result as &$row) {
            $row['gambar_url'] = $this->get_gambar_url($row['gambar_kursus']);
        }
        
        return $result;
    }

    /**
     * Hitung total kursus
     */
    public function count_kursus_by_guru($guru_id) {
        $this->db->where('guru_id', $guru_id);
        return $this->db->count_all_results('kursus');
    }

    /**
     * Ambil detail kursus by ID
     */
    public function get_kursus_by_id($kursus_id, $guru_id = null) {
        if (!$kursus_id) {
            return null;
        }
        
        $this->db->select('k.*, kk.nama_kategori, 
                          (SELECT COUNT(DISTINCT siswa_id) 
                           FROM enrollment 
                           WHERE kursus_id = k.kursus_id) as jumlah_siswa,
                          (SELECT COUNT(DISTINCT forum_id) 
                           FROM forum 
                           WHERE kursus_id = k.kursus_id) as jumlah_forum');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id');
        $this->db->where('k.kursus_id', $kursus_id);
        
        if ($guru_id) {
            $this->db->where('k.guru_id', $guru_id);
        }
        
        $result = $this->db->get()->row_array();
        
        if ($result) {
            $result['gambar_url'] = $this->get_gambar_url($result['gambar_kursus']);
            $result['biaya_formatted'] = 'Rp ' . number_format($result['biaya'], 0, ',', '.');
            $result['tanggal_format'] = $this->format_tanggal($result['tanggal_dibuat']);
            $result['status_badge'] = $this->get_status_badge($result['status_kursus']);
        }
        
        return $result;
    }

    /**
     * Simpan kursus baru
     */
    public function save_kursus($data) {
        // Validasi data
        if (empty($data)) {
            log_message('error', 'Data kursus kosong');
            return false;
        }
        
        // Pastikan field required ada
        $required_fields = ['kategori_id', 'guru_id', 'judul_kursus', 'deskripsi', 'biaya', 'durasi'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field])) {
                log_message('error', "Field {$field} tidak ada dalam data");
                return false;
            }
        }
        
        // Default values
        $defaults = [
            'detail' => '',
            'gambar_kursus' => 'default-course.jpg',
            'status_kursus' => 'active',
            'rating_rata2' => 0.00,
            'tanggal_dibuat' => date('Y-m-d H:i:s')
        ];
        
        $data = array_merge($defaults, $data);
        
        // Insert ke database
        $this->db->insert('kursus', $data);
        
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            $error = $this->db->error();
            log_message('error', 'Database Error: ' . print_r($error, true));
            return false;
        }
    }

    /**
     * Update kursus
     */
    public function update_kursus($kursus_id, $data, $guru_id = null) {
        if (!$kursus_id) {
            return false;
        }
        
        $this->db->where('kursus_id', $kursus_id);
        
        if ($guru_id) {
            $this->db->where('guru_id', $guru_id);
        }
        
        $result = $this->db->update('kursus', $data);
        
        if (!$result) {
            $error = $this->db->error();
            log_message('error', 'Update Error: ' . print_r($error, true));
        }
        
        return $result;
    }

    /**
     * Hapus kursus
     */
    public function delete_kursus($kursus_id, $guru_id = null) {
        if (!$kursus_id) {
            return false;
        }
        
        $this->db->where('kursus_id', $kursus_id);
        
        if ($guru_id) {
            $this->db->where('guru_id', $guru_id);
        }
        
        return $this->db->delete('kursus');
    }
    
    /**
     * Ambil kursus terbaru
     */
    public function get_kursus_terbaru($guru_id, $limit = 5) {
        $this->db->select('k.*, kk.nama_kategori');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->order_by('k.tanggal_dibuat', 'DESC');
        $this->db->limit($limit);
        
        $result = $this->db->get()->result_array();
        
        foreach ($result as &$row) {
            $row['gambar_url'] = $this->get_gambar_url($row['gambar_kursus']);
        }
        
        return $result;
    }
    
    /**
     * Cari kursus
     */
    public function search_kursus($guru_id, $keyword) {
        $this->db->select('k.*, kk.nama_kategori');
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kk', 'k.kategori_id = kk.kategori_id');
        $this->db->where('k.guru_id', $guru_id);
        $this->db->group_start();
        $this->db->like('k.judul_kursus', $keyword);
        $this->db->or_like('k.deskripsi', $keyword);
        $this->db->or_like('kk.nama_kategori', $keyword);
        $this->db->group_end();
        $this->db->order_by('k.tanggal_dibuat', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        foreach ($result as &$row) {
            $row['gambar_url'] = $this->get_gambar_url($row['gambar_kursus']);
        }
        
        return $result;
    }
    
    /**
     * Helper: Get gambar URL
     */
    private function get_gambar_url($gambar_kursus) {
        if (empty($gambar_kursus) || $gambar_kursus == 'default-course.jpg') {
            return base_url('assets/images/default-course.jpg');
        }
        
        $path = FCPATH . 'uploads/kursus/' . $gambar_kursus;
        if (file_exists($path)) {
            return base_url('uploads/kursus/' . $gambar_kursus);
        }
        
        return base_url('assets/images/default-course.jpg');
    }
    
    /**
     * Helper: Format tanggal
     */
    private function format_tanggal($datetime) {
        if (empty($datetime)) {
            return '';
        }
        
        $timestamp = strtotime($datetime);
        $today = strtotime('today');
        $yesterday = strtotime('yesterday');
        
        if ($timestamp >= $today) {
            return date('H:i', $timestamp) . ' (Hari ini)';
        } elseif ($timestamp >= $yesterday) {
            return date('H:i', $timestamp) . ' (Kemarin)';
        } else {
            return date('d M Y, H:i', $timestamp);
        }
    }
    
    /**
     * Helper: Get status badge
     */
    private function get_status_badge($status) {
        $badges = [
            'active' => '<span class="badge bg-success">Aktif</span>',
            'inactive' => '<span class="badge bg-danger">Nonaktif</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>'
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
?>