<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Materi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f8fc;
            font-family: 'Segoe UI', sans-serif;
        }
        .main {
            margin-left: 280px;
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
        }
        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .badge-pdf {
            background: #dcfce7;
            color: #166534;
        }
        .badge-video {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-link {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>

<?php $this->load->view('lpk/sidebar', ['menu' => 'materi']); ?>

<div class="main">

    <!-- Notifikasi -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Daftar Materi</h3>
            <p class="text-muted mb-0">Kursus ID: <?= $kursus_id ?></p>
        </div>
        <div>
            <a href="<?= base_url('lpk/kursus'); ?>" class="btn btn-secondary">
                ← Kembali ke Kursus
            </a>
        </div>
    </div>

    <!-- Tabel Materi -->
    <div class="card mb-4">
        <div class="card-body">
            <?php if (!empty($materi)): ?>
                <table class="table align-middle">
                    <thead class="text-muted small">
                        <tr>
                            <th>Judul Materi</th>
                            <th>Tipe</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materi as $m): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <?= $m['judul_materi']; ?>
                                </td>
                                <td>
                                    <?php if ($m['tipe_materi'] == 'pdf'): ?>
                                        <span class="badge badge-pdf px-3 py-2">PDF</span>
                                    <?php elseif ($m['tipe_materi'] == 'video'): ?>
                                        <span class="badge badge-video px-3 py-2">Video</span>
                                    <?php else: ?>
                                        <span class="badge badge-link px-3 py-2">Link</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small">
                                    <?= date('d-m-Y H:i', strtotime($m['tanggal_upload'])); ?>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <!-- Download Icon -->
                                        <a href="<?= base_url('uploads/materi/'.$m['konten']); ?>" 
                                           target="_blank"
                                           class="icon-btn border" 
                                           title="Download">
                                            <img src="<?= base_url('assets/image/icon/unduh.png'); ?>" width="18">
                                        </a>
                                        
                                        <!-- Edit Icon -->
                                        <a href="<?= base_url('lpk/materi/edit/'.$m['materi_id']); ?>" 
                                           class="icon-btn border"
                                           title="Edit">
                                            <img src="<?= base_url('assets/image/icon/edit.png'); ?>" width="18">
                                        </a>
                                        
                                        <!-- Hapus Icon -->
                                        <a href="<?= base_url('lpk/materi/hapus/'.$m['materi_id']); ?>" 
                                           class="icon-btn border"
                                           title="Hapus"
                                           onclick="return confirm('Hapus materi ini?')">
                                            <img src="<?= base_url('assets/image/icon/hapus.png'); ?>" width="18">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <img src="<?= base_url('assets/image/icon/file-empty.png'); ?>" width="64">
                    </div>
                    <h5 class="text-muted">Belum ada materi</h5>
                    <p class="text-muted small">Upload materi pertama untuk kursus ini</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>