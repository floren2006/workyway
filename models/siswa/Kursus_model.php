<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kursus_model extends CI_Model {

    public function get_user_data($user_id) {
        $this->db->select('u.*, s.*');
        $this->db->from('users u');
        $this->db->join('siswa s', 'u.user_id = s.user_id', 'left');
        $this->db->where('u.user_id', $user_id);
        return $this->db->get()->row();
    }

    public function get_kategori_list() {
        $this->db->select('*');
        $this->db->from('kategori_kursus');
        $this->db->order_by('nama_kategori', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_kursus($kategori = null, $kesulitan = null, $harga = null, $search = null, $siswa_id = null) {
        $this->db->select('
            k.kursus_id,
            k.judul_kursus,
            k.biaya,
            k.durasi,
            k.rating_rata2,
            k.deskripsi,
            k.gambar_kursus,
            kat.nama_kategori,
            CASE 
                WHEN k.guru_id IS NOT NULL THEN (
                    SELECT nama FROM users 
                    WHERE user_id = (
                        SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id
                    )
                )
                ELSE (
                    SELECT nama_lembaga FROM lpk WHERE lpk_id = k.lpk_id
                )
            END as instruktur,
            (SELECT COUNT(*) FROM enrollment WHERE kursus_id = k.kursus_id) as total_peserta,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM enrollment e2 
                    WHERE e2.kursus_id = k.kursus_id 
                    AND e2.siswa_id = ' . ($siswa_id ?: '0') . '
                ) THEN 1 
                ELSE 0 
            END as sudah_daftar
        ');
        
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kat', 'k.kategori_id = kat.kategori_id');
        $this->db->where('k.status_kursus', 'active');
        
        // Filter kategori
        if (!empty($kategori) && $kategori != 'semua') {
            $this->db->where('kat.nama_kategori', $kategori);
        }
        
        // Filter harga
        if (!empty($harga) && $harga != 'semua') {
            switch ($harga) {
                case 'gratis':
                    $this->db->where('k.biaya', 0);
                    break;
                case '<200':
                    $this->db->where('k.biaya <', 200000);
                    break;
                case '200-500':
                    $this->db->where('k.biaya >=', 200000);
                    $this->db->where('k.biaya <=', 500000);
                    break;
                case '>500':
                    $this->db->where('k.biaya >', 500000);
                    break;
            }
        }
        
        // Filter pencarian
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('k.judul_kursus', $search);
            $this->db->or_like('k.deskripsi', $search);
            $this->db->group_end();
        }
        
        // Filter kesulitan berdasarkan durasi
        if (!empty($kesulitan) && $kesulitan != 'semua') {
            switch ($kesulitan) {
                case 'pemula':
                    $this->db->where_in('k.durasi', ['1 Bulan', '1.5 Bulan', '2 Bulan']);
                    break;
                case 'menengah':
                    $this->db->where_in('k.durasi', ['2.5 Bulan', '3 Bulan']);
                    break;
                case 'lanjutan':
                    $this->db->where_in('k.durasi', ['3 Bulan', '4 Bulan']);
                    break;
            }
        }
        
        // EKSLUSI: Hanya tampilkan kursus yang belum didaftar
        if ($siswa_id) {
            $this->db->where("k.kursus_id NOT IN (
                SELECT kursus_id FROM enrollment 
                WHERE siswa_id = $siswa_id
            )");
        }
        
        $this->db->order_by('k.rating_rata2', 'DESC');
        return $this->db->get()->result();
    }

    public function get_kursus_detail($kursus_id, $siswa_id = null) {
        $this->db->select('
            k.*,
            kat.nama_kategori,
            CASE 
                WHEN k.guru_id IS NOT NULL THEN (
                    SELECT nama FROM users 
                    WHERE user_id = (
                        SELECT user_id FROM guru_freelance WHERE guru_id = k.guru_id
                    )
                )
                ELSE (
                    SELECT nama_lembaga FROM lpk WHERE lpk_id = k.lpk_id
                )
            END as instruktur,
            (SELECT COUNT(*) FROM enrollment WHERE kursus_id = k.kursus_id) as total_peserta,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM enrollment e2 
                    WHERE e2.kursus_id = k.kursus_id 
                    AND e2.siswa_id = ' . ($siswa_id ?: '0') . '
                ) THEN 1 
                ELSE 0 
            END as sudah_daftar
        ');
        
        $this->db->from('kursus k');
        $this->db->join('kategori_kursus kat', 'k.kategori_id = kat.kategori_id');
        $this->db->where('k.kursus_id', $kursus_id);
        
        return $this->db->get()->row();
    }


}