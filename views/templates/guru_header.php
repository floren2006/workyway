<?php
$segment = $this->uri->segment(2); // ambil method
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Dashboard Guru' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fb;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: #ffffff;
            border-right: 1px solid #eee;
            padding: 20px;
        }

        .sidebar h4 {
            font-weight: 700;
            margin-bottom: 30px;
        }

        .nav-link {
            color: #555;
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 8px 12px;
            display: block;
            text-decoration: none;
        }

        .nav-link.active,
        .nav-link:hover {
            background: #f0f4ff;
            color: #4361ee;
            font-weight: 600;
        }

        .content {
            flex: 1;
            padding: 30px;
        }
    </style>
</head>
<body>

<div class="wrapper">

<aside class="sidebar">
    <h4>Panel Guru</h4>

    <a href="<?= base_url('index.php/guru/dashboard') ?>"
       class="nav-link <?= ($segment == '' || $segment == 'index') ? 'active' : '' ?>">
        <i class="fas fa-home me-2"></i> Dashboard
    </a>

    <a href="<?= base_url('index.php/guru/profil') ?>"
       class="nav-link <?= ($segment == 'profil') ? 'active' : '' ?>">
        <i class="fas fa-user-circle me-2"></i> Profil Guru
    </a>

    <a href="<?= base_url('index.php/guru/kursus') ?>"
       class="nav-link <?= ($segment == 'kursus') ? 'active' : '' ?>">
        <i class="fas fa-book-open me-2"></i> Manajemen Kursus
    </a>

    <a href="<?= base_url('index.php/guru/daftar_siswa') ?>"
       class="nav-link <?= ($segment == 'daftar_siswa') ? 'active' : '' ?>">
        <i class="fas fa-users me-2"></i> Daftar Siswa
    </a>

    <a href="<?= base_url('index.php/guru/penilaian') ?>"
       class="nav-link <?= ($segment == 'penilaian') ? 'active' : '' ?>">
        <i class="fas fa-star me-2"></i> Penilaian
    </a>

    <a href="<?= base_url('index.php/guru/pendapatan') ?>"
       class="nav-link <?= ($segment == 'pendapatan') ? 'active' : '' ?>">
        <i class="fas fa-dollar-sign me-2"></i> Pendapatan
    </a>

    <a href="<?= base_url('index.php/guru/forum') ?>"
       class="nav-link <?= ($segment == 'forum') ? 'active' : '' ?>">
        <i class="fas fa-comments me-2"></i> Forum Diskusi
    </a>
</aside>

<main class="content">
