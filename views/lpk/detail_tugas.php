<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .info-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .badge {
            font-size: 0.85em;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?= $this->include('sidebar'); ?>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <div>
                        <h4 class="mb-0"><?= $title; ?></h4>
                        <p class="text-muted mb-0">Detail lengkap tugas</p>
                    </div>
                    <div>
                        <a href="<?= base_url('tugas'); ?>" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="<?= base_url('tugas/edit/' . $tugas['tugas_id']); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="container-fluid py-4">
                <div class="row">
                    <!-- Main Information -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Informasi Tugas</h5>
                                <span class="badge bg-<?= $tugas['status'] == 'aktif' ? 'success' : ($tugas['status'] == 'selesai' ? 'info' : 'secondary'); ?>">
                                    <?= ucfirst($tugas['status']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h4 class="mb-3"><?= htmlspecialchars($tugas['judul_tugas']); ?></h4>
                                
                                <div class="mb-4">
                                    <h6><i class="fas fa-align-left me-2"></i> Deskripsi Tugas</h6>
                                    <div class="info-box">
                                        <?= nl2br(htmlspecialchars($tugas['deskripsi'])); ?>
                                    </div>
                                </div>

                                <!-- Informasi Detail -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6><i class="fas fa-book me-2"></i> Materi</h6>
                                        <div class="info-box">
                                            <strong><?= htmlspecialchars($tugas['judul_
                                            ']); ?></strong>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <h6><i class="fas fa-graduation-cap me-2"></i> Kursus</h6>
                                        <div class="info-box">
                                            <strong><?= htmlspecialchars($tugas['judul_kursus']); ?></strong>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <h6><i class="fas fa-calendar-times me-2"></i> Deadline</h6>
                                        <div class="info-box">
                                            <?php
                                                $deadline = new DateTime($tugas['deadline']);
                                                $now = new DateTime();
                                                $interval = $now->diff($deadline);
                                                $isOverdue = $interval->invert == 1;
                                                $isNear = !$isOverdue && $interval->days <= 3;
                                            ?>
                                            <div class="<?= $isOverdue ? 'text-danger' : ($isNear ? 'text-warning' : ''); ?>">
                                                <strong><?= date('d M Y H:i', strtotime($tugas['deadline'])); ?></strong>
                                                <?php if($isOverdue): ?>
                                                    <br><small class="text-danger">(Deadline telah lewat)</small>
                                                <?php elseif($isNear): ?>
                                                    <br><small class="text-warning">(<?= $interval->days; ?> hari lagi)</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <h6><i class="fas fa-star me-2"></i> Nilai Maksimal</h6>
                                        <div class="info-box">
                                            <strong><?= $tugas['max_score']; ?> / 100</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <h6><i class="fas fa-users me-2"></i> Tipe Tugas</h6>
                                        <div class="info-box">
                                            <strong><?= $tugas['tipe_tugas'] == 'individual' ? 'Individual' : 'Kelompok'; ?></strong>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <h6><i class="fas fa-hashtag me-2"></i> ID Tugas</h6>
                                        <div class="info-box">
                                            <strong>#<?= $tugas['tugas_id']; ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Template -->
                        <?php if($tugas['file_template']): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-file-archive me-2"></i> File Template</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-download fa-2x text-primary me-3"></i>
                                        <div>
                                            <strong><?= $tugas['file_template']; ?></strong>
                                            <div class="text-muted">File template tugas</div>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('tugas/download-template/' . $tugas['tugas_id']); ?>" 
                                           class="btn btn-primary">
                                            <i class="fas fa-download me-1"></i> Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Side Information -->
                    <div class="col-lg-4">
                        <!-- Status & Actions -->
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i> Aksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('tugas/edit/' . $tugas['tugas_id']); ?>" 
                                       class="btn btn-warning">
                                        <i class="fas fa-edit me-1"></i> Edit Tugas
                                    </a>
                                    
                                    <?php if($tugas['status'] == 'aktif'): ?>
                                        <a href="<?= base_url('penilaian?tugas=' . $tugas['tugas_id']); ?>" 
                                           class="btn btn-info">
                                            <i class="fas fa-check-circle me-1"></i> Lihat Pengumpulan
                                        </a>
                                    <?php endif; ?>
                                    
                                    <button type="button" class="btn btn-danger" 
                                            onclick="if(confirm('Apakah Anda yakin ingin menonaktifkan tugas ini?')) window.location.href='<?= base_url('tugas/delete/' . $tugas['tugas_id']); ?>'">
                                        <i class="fas fa-ban me-1"></i> Nonaktifkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Sistem -->
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Sistem</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6>Dibuat</h6>
                                    <div class="info-box">
                                        <div><?= date('d M Y', strtotime($tugas['created_at'])); ?></div>
                                        <small class="text-muted"><?= date('H:i', strtotime($tugas['created_at'])); ?></small>
                                    </div>
                                </div>
                                
                                <?php if(!empty($tugas['updated_at'])): ?>
                                <div class="mb-3">
                                    <h6>Terakhir Diupdate</h6>
                                    <div class="info-box">
                                        <div><?= date('d M Y', strtotime($tugas['updated_at'])); ?></div>
                                        <small class="text-muted"><?= date('H:i', strtotime($tugas['updated_at'])); ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div>
                                    <h6>Pembuat</h6>
                                    <div class="info-box">
                                        <div><?= htmlspecialchars($tugas['nama_pembuat'] ?? 'System'); ?></div>
                                        <?php if(!empty($tugas['nama_pengupdate'])): ?>
                                            <small class="text-muted">Terakhir diupdate oleh: <?= htmlspecialchars($tugas['nama_pengupdate']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Statistik</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <div class="display-6 fw-bold">
                                            <?= $tugas['status'] == 'aktif' ? 'Aktif' : ($tugas['status'] == 'selesai' ? 'Selesai' : 'Nonaktif'); ?>
                                        </div>
                                        <div class="text-muted">Status Tugas</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="fw-bold"><?= $tugas['max_score']; ?></div>
                                            <small class="text-muted">Nilai Maks</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold">
                                                <?= $tugas['tipe_tugas'] == 'individual' ? 'Individu' : 'Kelompok'; ?>
                                            </div>
                                            <small class="text-muted">Tipe</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('d-none');
        });
    </script>
</body>
</html>