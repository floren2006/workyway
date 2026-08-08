<?php
$menu = $menu ?? '';
?>

<div class="sidebar">
    <!-- LOGO -->
    <div class="sidebar-header">
        <span>Panel LPK</span>
    </div>

    <!-- MENU -->
    <nav class="sidebar-menu">

        <a href="<?= base_url('lpk/dashboard_lpk'); ?>"
           class="<?= ($menu == 'dashboard') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/dblpk.png'); ?>">
            <span>Dashboard</span>
        </a>

        <a href="<?= base_url('lpk/profil_lpk'); ?>"
           class="<?= ($menu == 'profil') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/profillpk.png'); ?>">
            <span>Profil Lembaga</span>
        </a>

        <a href="<?= base_url('lpk/kursus'); ?>"
           class="<?= ($menu == 'kursus') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/mnjkursuslpk.png'); ?>">
            <span>Manajemen Kursus</span>
        </a>

        <a href="<?= base_url('lpk/instruktur'); ?>"
           class="<?= ($menu == 'instruktur') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/instruktur.png'); ?>">
            <span>Instruktur</span>
        </a>

        <a href="<?= base_url('lpk/siswa'); ?>"
           class="<?= ($menu == 'siswa') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/siswalpk.png'); ?>">
            <span>Daftar Siswa</span>
        </a>

        <a href="<?= base_url('lpk/tugas'); ?>"
           class="<?= ($menu == 'penilaian') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/tugas.png'); ?>">
            <span>Pemberian Tugas</span>
        </a>

        <a href="<?= base_url('lpk/penilaian_lpk'); ?>"
           class="<?= ($menu == 'penilaian') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/penilaian.png'); ?>">
            <span>Penilaian Tugas</span>
        </a>

        <a href="<?= base_url('lpk/laporan'); ?>"
           class="<?= ($menu == 'laporan') ? 'active' : '' ?>">
            <img src="<?= base_url('assets/image/icon/laplpk.png'); ?>">
            <span>Laporan Kursus</span>
        </a>

    </nav>
</div>

<style> 
   .sidebar {
    width:260px;
    height:100vh;
    background:#fff;
    position:fixed;
    top:0;
    left:0;
    padding:20px;
    display:flex;
    flex-direction:column;
    box-shadow:0 0 15px rgba(0,0,0,.05);
}

.sidebar-header {
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:30px;
}

.sidebar-header .logo {
    width:42px;
    height:42px;
    object-fit:contain;
}

.sidebar-header span {
    font-size:18px;
    font-weight:700;
    color:#2563eb;
}

.sidebar-menu {
    display:flex;
    flex-direction:column;
    gap:6px;
}

.sidebar-menu a {
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 14px;
    border-radius:10px;
    color:#333;
    text-decoration:none;
    transition:.2s ease;
}

.sidebar-menu a img {
    width:20px;
    height:20px;
    object-fit:contain;
}

.sidebar-menu a:hover {
    background:#eef4ff;
    transform:translateX(4px);
}

.sidebar-menu a.active {
    background:#2563eb;
    color:#fff;
}

.sidebar-menu a.active img {
    filter:brightness(0) invert(1);
}


</style>