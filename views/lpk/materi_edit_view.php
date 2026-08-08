<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Materi</title>
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
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .file-preview {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border: 2px dashed #dee2e6;
        }
        .badge-tipe {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
        <h3 class="fw-bold">Edit Materi</h3>
        <a href="<?= base_url('lpk/materi/'.$materi['kursus_id']); ?>" class="btn btn-secondary">
            ← Kembali ke Daftar Materi
        </a>
    </div>

    <!-- Form Edit -->
    <div class="card">
        <div class="card-body p-4">
            <form method="post" action="<?= base_url('lpk/materi/edit/'.$materi['materi_id']); ?>" 
                  enctype="multipart/form-data">
                
                <div class="mb-4">
                    <label class="form-label">Judul Materi</label>
                    <input type="text" 
                           name="judul_materi" 
                           class="form-control" 
                           value="<?= htmlspecialchars($materi['judul_materi']); ?>"
                           required>
                    <div class="form-text">
                        Judul akan tampil di daftar materi
                    </div>
                </div>
                
                <!-- Informasi File Saat Ini -->
                <div class="mb-4">
                    <label class="form-label">File Saat Ini</label>
                    <div class="file-preview">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <?php if ($materi['tipe_materi'] == 'pdf'): ?>
                                        <img src="<?= base_url('assets/image/icon/pdf.png'); ?>" width="40">
                                    <?php elseif ($materi['tipe_materi'] == 'video'): ?>
                                        <img src="<?= base_url('assets/image/icon/video.png'); ?>" width="40">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/image/icon/file.png'); ?>" width="40">
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <strong class="me-3"><?= $materi['judul_materi']; ?></strong>
                                        <?php if ($materi['tipe_materi'] == 'pdf'): ?>
                                            <span class="badge-tipe badge-pdf">PDF</span>
                                        <?php elseif ($materi['tipe_materi'] == 'video'): ?>
                                            <span class="badge-tipe badge-video">VIDEO</span>
                                        <?php else: ?>
                                            <span class="badge-tipe badge-link">FILE</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted small">
                                        Nama file: <code><?= $materi['konten']; ?></code><br>
                                        Upload: <?= date('d-m-Y H:i', strtotime($materi['tanggal_upload'])); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="<?= base_url('uploads/materi/'.$materi['konten']); ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary mb-2">
                                    <img src="<?= base_url('assets/image/icon/unduh.png'); ?>" width="14" class="me-1">
                                    Download
                                </a>
                                <div class="text-muted small">
                                    <?php 
                                    $file_path = FCPATH . 'uploads/materi/' . $materi['konten'];
                                    if (file_exists($file_path)) {
                                        $file_size = filesize($file_path);
                                        if ($file_size < 1024) {
                                            echo 'Ukuran: ' . $file_size . ' bytes';
                                        } elseif ($file_size < 1048576) {
                                            echo 'Ukuran: ' . round($file_size/1024, 2) . ' KB';
                                        } else {
                                            echo 'Ukuran: ' . round($file_size/1048576, 2) . ' MB';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Upload File Baru -->
                <div class="mb-4">
                    <label class="form-label">Ganti File</label>
                    <div class="input-group">
                        <input type="file" 
                               name="file_materi" 
                               class="form-control"
                               accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.mp4,.mov,.avi,.zip,.rar">
                        <span class="input-group-text">
                            <img src="<?= base_url('assets/image/icon/unduh.png'); ?>" width="16">
                        </span>
                    </div>
                    <div class="form-text">
                        <strong>Tips:</strong><br>
                        1. Kosongkan jika tidak ingin mengganti file<br>
                        2. Jika upload file baru, tipe materi akan otomatis menyesuaikan (PDF/Video/File)<br>
                        3. File lama akan otomatis dihapus dari server<br>
                        4. Format: PDF, Word, PowerPoint, Video, ZIP, RAR (max 20MB)
                    </div>
                </div>
                
                <!-- Info Tipe Otomatis -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <img src="<?= base_url('assets/image/icon/info.png'); ?>" width="20" class="me-2">
                        <div>
                            <strong>Tipe materi otomatis</strong><br>
                            <small>Tipe materi (PDF/Video/File) akan otomatis ditentukan berdasarkan file yang diupload. Jika tidak upload file baru, tipe tetap sama.</small>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('lpk/materi/'.$materi['kursus_id']); ?>" 
                       class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>