<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper('url');
        $this->load->model('Login_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            
            // Redirect berdasarkan role termasuk admin
            switch ($role) {
                case 'siswa':
                    redirect('siswa/dashboard');
                case 'lpk':
                    redirect('lpk/dashboard_lpk');
                case 'guru':
                    redirect('guru/dashboard');
                case 'admin':
                    redirect('admin/dashboard');
                default:
                    redirect('landing');
            }
        }

        $this->load->view('header');
        $this->load->view('login_view');
    }

    public function process()
    {
        $email    = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->Login_model->check_login($email, $password);

        if ($user) {
            $this->upgrade_password_hash($user->user_id, $password, $user->password);
            
            // Bersihkan session lama
            $this->session->unset_userdata([
                'user_id', 'nama', 'email', 'role', 
                'lpk_id', 'guru_id', 'siswa_id', 'logged_in'
            ]);
            
            // Session dasar untuk semua user
            $session_data = [
                'user_id'   => $user->user_id,
                'nama'      => $user->nama,
                'email'     => $user->email,
                'role'      => $user->role,
                'logged_in' => TRUE
            ];
            
            // TAMBAHKAN DATA SESUAI ROLE
            switch ($user->role) {
                case 'lpk':
                    $this->db->where('user_id', $user->user_id);
                    $lpk = $this->db->get('lpk')->row();
                    $session_data['lpk_id'] = $lpk ? $lpk->lpk_id : 0;
                    break;
                    
                case 'siswa':
                    $this->db->where('user_id', $user->user_id);
                    $siswa = $this->db->get('siswa')->row();
                    $session_data['siswa_id'] = $siswa ? $siswa->siswa_id : 0;
                    break;
                    
                case 'guru':
                    $this->db->where('user_id', $user->user_id);
                    $guru = $this->db->get('guru_freelance')->row();
                    $session_data['guru_id'] = $guru ? $guru->guru_id : 0;
                    break;
                    
                case 'admin':
                    // Admin tidak perlu data tambahan
                    break;
            }
            
            $this->session->set_userdata($session_data);
            $this->session->set_flashdata('pesan_sukses', 'Login berhasil');

            // Redirect berdasarkan role termasuk admin
            switch ($user->role) {
                case 'siswa':
                    redirect('siswa/dashboard');
                case 'lpk':
                    redirect('lpk/dashboard_lpk');
                case 'guru':
                    redirect('guru/dashboard');
                case 'admin':
                    redirect('admin/dashboard');
                default:
                    redirect('landing');
            }

        } else {
            $this->session->set_flashdata('pesan_gagal', 'Email atau password salah');
            redirect('login');
        }
    }
    
    private function upgrade_password_hash($user_id, $password, $current_hash)
    {
        // Cek jika hash masih MD5, SHA1, atau double MD5
        if (md5($password) === $current_hash || 
            sha1($password) === $current_hash ||
            md5(md5($password)) === $current_hash) {
            
            // Upgrade ke password_hash
            $this->Login_model->update_password_hash($user_id, $password);
        }
    }
    
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('landing');
    }
}