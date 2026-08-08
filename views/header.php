<?php
$page = $page ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'WorkyWay'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/landing.css'); ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">

        <!-- LOGO (SELALU ADA) -->
        <a class="navbar-brand text-primary fw-bold" href="<?= base_url('landing'); ?>">
            <i class="fas fa-graduation-cap"></i> WorkyWay
        </a>

        <?php if (!in_array($page, ['login', 'register'])): ?>
            <!-- TOGGLER -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- NAVBAR -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- MENU TENGAH -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('landing'); ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#courses">Kursus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer">Kontak</a>
                    </li>
                </ul>

                <!-- MENU KANAN -->
                <ul class="navbar-nav ms-auto align-items-center">

                    <?php if ($this->session->userdata('logged_in')): ?>
                        <!-- JIKA SUDAH LOGIN -->
                        <li class="nav-item me-2">
                            <span class="nav-link text-muted">
                                Halo, <strong><?= $this->session->userdata('nama'); ?></strong>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger"
                               href="<?= base_url('logout'); ?>"
                               onclick="return confirm('Yakin ingin logout?')">
                                Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- JIKA BELUM LOGIN -->
                        <li class="nav-item me-2">
                            <a class="nav-link" href="<?= base_url('login'); ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="<?= base_url('register'); ?>">
                                Register
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        <?php endif; ?>

    </div>
</nav>

<!-- FLASH MESSAGE -->
<?php if ($this->session->flashdata('pesan_sukses')): ?>
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <?= $this->session->flashdata('pesan_sukses'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <?= $this->session->flashdata('pesan_gagal'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
