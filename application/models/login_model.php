<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Check login credentials with multiple hash support
     */
    public function check_login($email, $password)
    {
        $user = $this->db
            ->where('email', $email)
            ->where('status_aktif', 1)
            ->get('users')
            ->row();

        if (!$user) return false;

        // Cek password dengan multiple hash methods
        if ($this->verify_password($password, $user->password)) {
            return $user;
        }

        return false;
    }

    /**
     * Verify password with multiple hash algorithms
     */
    private function verify_password($input_password, $stored_hash)
    {
        // Method 1: MD5 (legacy)
        if (md5($input_password) === $stored_hash) {
            return true;
        }
        
        // Method 2: SHA1
        if (sha1($input_password) === $stored_hash) {
            return true;
        }
        
        // Method 3: password_hash (PHP default)
        if (password_verify($input_password, $stored_hash)) {
            return true;
        }
        
        // Method 4: Check for double MD5 (jika ada)
        if (md5(md5($input_password)) === $stored_hash) {
            return true;
        }
        
        // Method 5: Check MD5 dengan salt jika ada pattern tertentu
        // Contoh: md5($input_password . 'salt')
        // Sesuaikan dengan implementasi Anda
        
        return false;
    }
    
    /**
     * Update user password dengan hash yang lebih aman (optional)
     */
    public function update_password_hash($user_id, $new_password)
    {
        // Hash dengan password_hash (rekomendasi)
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $this->db->where('user_id', $user_id)
                ->update('users', ['password' => $new_hash]);
        
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Get user by email
     */
    public function get_user_by_email($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }
    
    /**
     * Register new user with modern password hash
     */
    public function register($data)
    {
        // Gunakan password_hash untuk user baru (rekomendasi)
        // atau gunakan hash sesuai kebutuhan sistem
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Jika ingin menggunakan SHA1 atau MD5 untuk kompatibilitas:
        // $data['password'] = sha1($data['password']);
        // atau
        // $data['password'] = md5($data['password']);
        
        $data['tanggal_daftar'] = date('Y-m-d H:i:s');
        
        // Insert ke tabel users
        $this->db->insert('users', $data);
        $user_id = $this->db->insert_id();
        
        // Jika berhasil, tambahkan ke tabel sesuai role
        if ($user_id) {
            $role_data = array(
                'user_id' => $user_id
            );
            
            switch ($data['role']) {
                case 'siswa':
                    $role_data['tanggal_lahir'] = null;
                    $role_data['pendidikan_terakhir'] = null;
                    $this->db->insert('siswa', $role_data);
                    break;
                    
                case 'guru':
                    $role_data['keahlian'] = '[]';
                    $role_data['pengalaman'] = null;
                    $role_data['portofolio'] = null;
                    $role_data['status_verifikasi'] = 'pending';
                    $this->db->insert('guru_freelance', $role_data);
                    break;
                    
                case 'lpk':
                    $role_data['nama_lembaga'] = $data['name'];
                    $role_data['deskripsi'] = null;
                    $role_data['nomor_izin'] = null;
                    $role_data['status_verifikasi'] = 'pending';
                    $this->db->insert('lpk', $role_data);
                    break;
            }
        }
        
        return $user_id;
    }
    
    /**
     * Check if email already exists
     */
    public function email_exists($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return ($query->num_rows() > 0);
    }
}