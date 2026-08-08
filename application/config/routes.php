<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'landing';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'login';
$route['login/process'] = 'login/process';
$route['logout'] = 'login/logout';
$route['kursus'] = 'landing';

$route['register']        = 'register/index';
$route['register/siswa']  = 'register/siswa';
$route['register/guru']   = 'register/guru';
$route['register/lpk']    = 'register/lpk';

$route['siswa/dashboard_siswa'] = 'siswa/dashboard_siswa';

$route['lpk/materi/(:num)'] = 'lpk/materi/index/$1';

/* Dashboard */
$route['siswa/dashboard'] = 'siswa/dashboard';
// Di application/config/routes.php, tambahkan:
$route['dashboard/kursus_saya'] = 'dashboard/kursus_saya';
$route['dashboard/submit_rating'] = 'dashboard/submit_rating';
$route['dashboard/get_rating_form/(:num)'] = 'dashboard/get_rating_form/$1';

// PROFIL SISWA (controller di folder siswa)
$route['siswa/profil'] = 'siswa/profil_siswa/index';
$route['siswa/profil/update'] = 'siswa/profil_siswa/update_profil';
$route['siswa/profil/password'] = 'siswa/profil_siswa/update_password';
$route['siswa/profil/sertifikat/(:num)'] = 'siswa/profil_siswa/download_sertifikat/$1';

/* =======================
   MODUL
   ======================= */
$route['siswa/modul/(:num)'] = 'siswa/modul/index/$1';
$route['siswa/modul/materi/(:num)'] = 'siswa/modul/materi/$1';

$route['siswa/modul/update_progress'] = 'siswa/modul/update_progress';
$route['siswa/modul/tambah_diskusi'] = 'siswa/modul/tambah_diskusi';
$route['siswa/modul/tambah_balasan'] = 'siswa/modul/tambah_balasan';
// Rating routes for siswa
$route['siswa/rating/submit'] = 'siswa/Rating/submit';
$route['siswa/rating/get_form/(:num)'] = 'siswa/Rating/get_form/$1';
$route['siswa/rating/get_reviews/(:num)'] = 'siswa/Rating/get_reviews/$1';
$route['siswa/rating/can_rate/(:num)'] = 'siswa/Rating/can_rate/$1';


/* =======================
   PENDAFTARAN
   ======================= */
$route['pendaftaran/checkout/(:num)'] = 'pendaftaran/checkout/$1';
$route['pendaftaran/process_payment'] = 'pendaftaran/process_payment';
$route['siswa/midtrans/process'] = 'siswa/midtrans/process';
$route['midtrans_callback'] = 'midtrans_callback/index';


/* =======================
   LPK
   ======================= */
$route['lpk/materi/(:num)'] = 'lpk/materi/index/$1';
// Routes untuk LPK
$route['lpk/penilaian'] = 'lpk/Penilaian_lpk';
$route['lpk/penilaian/detail/(:num)'] = 'lpk/Penilaian_lpk/detail/$1';
$route['lpk/penilaian/simpan_nilai'] = 'lpk/Penilaian_lpk/simpan_nilai';
$route['lpk/penilaian/statistik'] = 'lpk/Penilaian_lpk/statistik';

// API Routes
$route['api/kursus'] = 'landing/get_kursus_ajax';

// ============================================
// ROUTES UNTUK GURU FREELANCE (FIXED)
// ============================================

// Dashboard - Controller: guru/Dashboard.php
$route['dashboard'] = 'guru/dashboard';
$route['dashboard/refresh-data'] = 'guru/dashboard/refresh_data';
$route['dashboard/update-time'] = 'guru/dashboard/update_time';

// Profil - Controller: guru/Profil.php
$route['profil'] = 'guru/profil';
$route['profil/edit'] = 'guru/profil/edit';

// ============================================
// KURSUS MANAGEMENT ROUTES
// ============================================

// Kursus - Controller: guru/Kursus.php
$route['kursus'] = 'guru/kursus/list_kursus';
$route['kursus/list'] = 'guru/kursus/list_kursus';
$route['kursus/list_kursus'] = 'guru/kursus/list_kursus';
$route['kursus/tambah'] = 'guru/kursus/tambah';
$route['kursus/simpan_kursus'] = 'guru/kursus/simpan_kursus';
$route['kursus/edit/(:num)'] = 'guru/kursus/edit_kursus/$1';
$route['kursus/update/(:num)'] = 'guru/kursus/update_kursus/$1';
$route['kursus/hapus/(:num)'] = 'guru/kursus/hapus_kursus/$1';

// ============================================
// ROUTES LAINNYA
// ============================================

// Forum - Controller: guru/Forum.php
$route['forum'] = 'guru/forum';
$route['forum/kirim-pesan'] = 'guru/forum/kirim_pesan';
$route['forum/hapus/(:num)'] = 'guru/forum/hapus_pesan/$1';

// **PERBAIKAN: GANTI DENGAN INI UNTUK SISWA**
// Tambahkan di routes.php setelah baris 32:
$route['guru/daftar_siswa'] = 'guru/siswa/daftar_siswa';
$route['guru/siswa'] = 'guru/siswa';
$route['guru/siswa/daftar_siswa'] = 'guru/siswa/daftar_siswa';
$route['guru/siswa/detail_siswa/(:num)'] = 'guru/siswa/detail_siswa/$1';

// Penilaian - Controller: guru/Penilaian.php
$route['penilaian'] = 'guru/penilaian';

// Pendapatan - Controller: guru/Pendapatan.php
$route['pendapatan'] = 'guru/pendapatan';

// Materi - Controller: guru/Materi.php
$route['materi'] = 'guru/materi';
$route['materi/(:num)'] = 'guru/materi/materi/$1';

// ============================================
// ROUTES KOMPATIBILITAS (LAMA)
// ============================================

// Route lama untuk kompatibilitas
$route['guru/dashboard'] = 'guru/dashboard';
$route['guru/profil'] = 'guru/profil';
$route['guru/profil/edit'] = 'guru/profil/edit';

// Route lama untuk Kursus
$route['guru/kursus'] = 'guru/kursus/list_kursus';
$route['guru/kursus/list_kursus'] = 'guru/kursus/list_kursus';
$route['guru/kursus/tambah'] = 'guru/kursus/tambah';
$route['guru/kursus/simpan_kursus'] = 'guru/kursus/simpan_kursus';
$route['guru/kursus/edit_kursus/(:num)'] = 'guru/kursus/edit_kursus/$1';
$route['guru/kursus/update_kursus/(:num)'] = 'guru/kursus/update_kursus/$1';
$route['guru/kursus/hapus_kursus/(:num)'] = 'guru/kursus/hapus_kursus/$1';

// Forum - Controller: guru/Forum.php
$route['forum'] = 'guru/forum';
$route['forum/kirim-pesan'] = 'guru/forum/kirim_pesan';
$route['forum/hapus/(:num)'] = 'guru/forum/hapus_pesan/$1';

// Route lama untuk kompatibilitas
$route['guru/forum'] = 'guru/forum';
$route['guru/forum/kirim-pesan'] = 'guru/forum/kirim_pesan';
$route['guru/forum/hapus/(:num)'] = 'guru/forum/hapus_pesan/$1';

// **HAPUS ATAU KOMENTARI ROUTE INI:**
// $route['siswa'] = 'siswa/index';           // HAPUS BARIS INI
// $route['siswa/daftar_siswa'] = 'siswa/daftar_siswa'; // HAPUS
// $route['siswa/detail_siswa/(:num)'] = 'siswa/detail_siswa/$1'; // HAPUS

// Route lama untuk Penilaian
// Penilaian - Controller: guru/Penilaian.php
$route['penilaian'] = 'guru/penilaian';
$route['penilaian/detail/(:num)'] = 'guru/penilaian/detail/$1';
$route['penilaian/simpan_nilai'] = 'guru/penilaian/simpan_nilai';

// Route lama untuk kompatibilitas
$route['guru/penilaian'] = 'guru/penilaian';
$route['guru/penilaian/detail/(:num)'] = 'guru/penilaian/detail/$1';
$route['guru/penilaian/simpan_nilai'] = 'guru/penilaian/simpan_nilai';

// Hapus route untuk pemberian_nilai jika sudah ada
// $route['pemberian_nilai/(:num)'] = 'guru/pemberian_nilai/detail/$1';
// $route['pemberian_nilai/simpan_nilai'] = 'guru/pemberian_nilai/simpan_nilai';

// Route lama untuk Pendapatan
$route['pendapatan'] = 'guru/pendapatan';
$route['guru/pendapatan'] = 'guru/pendapatan';

// Route lama untuk Materi
$route['guru/materi'] = 'guru/materi';
$route['guru/materi/(:num)'] = 'guru/materi/materi/$1';

// Guru Routes
$route['guru/penilaian'] = 'guru/Penilaian';
$route['guru/penilaian/index'] = 'guru/Penilaian';
$route['guru/penilaian/detail/(:num)'] = 'guru/Penilaian/detail/$1';
$route['guru/penilaian/simpan_nilai'] = 'guru/Penilaian/simpan_nilai';
$route['guru/penilaian/download_file/(:num)'] = 'guru/Penilaian/download_file/$1';
$route['guru/penilaian/filter'] = 'guru/Penilaian/filter';
$route['guru/penilaian/filter/(:num)'] = 'guru/Penilaian/filter/$1';

$route['admin'] = 'admin/Admin_dashboard';
$route['admin/dashboard'] = 'admin/Admin_dashboard';
$route['admin/dashboard/refresh_stats'] = 'admin/Admin_dashboard/refresh_stats';

// ==============================================
// ROUTES UNTUK ADMIN MANAJEMEN PENGGUNA
// ==============================================

$route['admin/manajemen_pengguna'] = 'admin/Admin_manajemen_pengguna';
$route['admin/manajemen_pengguna/index'] = 'admin/Admin_manajemen_pengguna/index';
$route['admin/manajemen_pengguna/search'] = 'admin/Admin_manajemen_pengguna/search';
$route['admin/manajemen_pengguna/update_status/(:num)/(:num)'] = 'admin/Admin_manajemen_pengguna/update_status/$1/$2';
$route['admin/manajemen_pengguna/delete_user/(:num)'] = 'admin/Admin_manajemen_pengguna/delete_user/$1';


// ==============================================
// ROUTES UNTUK ADMIN MANAJEMEN KURSUS
// ==============================================

$route['admin/manajemen_kursus'] = 'admin/Admin_manajemen_kursus';
$route['admin/manajemen_kursus/index'] = 'admin/Admin_manajemen_kursus/index';
$route['admin/manajemen_kursus/search'] = 'admin/Admin_manajemen_kursus/search';
$route['admin/manajemen_kursus/approve/(:num)'] = 'admin/Admin_manajemen_kursus/approve/$1';
$route['admin/manajemen_kursus/reject/(:num)'] = 'admin/Admin_manajemen_kursus/reject/$1';
$route['admin/manajemen_kursus/hapus/(:num)'] = 'admin/Admin_manajemen_kursus/hapus/$1';
$route['admin/manajemen_kursus/tambah_kategori'] = 'admin/Admin_manajemen_kursus/tambah_kategori';
$route['admin/manajemen_kursus/edit_kategori/(:num)'] = 'admin/Admin_manajemen_kursus/edit_kategori/$1';
$route['admin/manajemen_kursus/update_kategori/(:num)'] = 'admin/Admin_manajemen_kursus/update_kategori/$1';
$route['admin/manajemen_kursus/hapus_kategori/(:num)'] = 'admin/Admin_manajemen_kursus/hapus_kategori/$1';
$route['admin/manajemen_kursus/tambah_kursus'] = 'admin/Admin_manajemen_kursus/tambah_kursus';

// ==============================================
// ROUTES UNTUK ADMIN MANAJEMEN TRANSAKSI
// ==============================================

$route['admin/manajemen_transaksi'] = 'admin/Admin_manajemen_transaksi';
$route['admin/manajemen_transaksi/index'] = 'admin/Admin_manajemen_transaksi/index';
$route['admin/manajemen_transaksi/detail/(:num)'] = 'admin/Admin_manajemen_transaksi/detail/$1';
$route['admin/manajemen_transaksi/update_status'] = 'admin/Admin_manajemen_transaksi/update_status';
$route['admin/manajemen_transaksi/export_excel'] = 'admin/Admin_manajemen_transaksi/export_excel';

// ==============================================
// ROUTES UNTUK ADMIN LAPORAN & STATISTIK
// ==============================================

$route['admin/laporan'] = 'admin/Admin_laporan';
$route['admin/laporan/index'] = 'admin/Admin_laporan/index';
$route['admin/laporan/download'] = 'admin/Admin_laporan/download';
$route['admin/laporan/debug'] = 'admin/Admin_laporan/debug'; // Untuk testing

// ==============================================
// ROUTES UNTUK HALAMAN LAINNYA
// ==============================================

$route['admin/pengaturan'] = 'admin/Admin_dashboard/pengaturan';
$route['admin/login'] = 'admin/Admin_dashboard/login';
$route['admin/logout'] = 'admin/Admin_dashboard/logout';