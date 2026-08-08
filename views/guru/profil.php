<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Guru - Kursus Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #dee2e6;
        }

        .portofolio-btn {
            transition: all 0.3s ease;
        }

        .portofolio-btn:hover {
            transform: translateY(-2px);
        }

        .stat-card {
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: white;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }
        
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>

<?php if ($this->session->flashdata('message')): ?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo $this->session->flashdata('message_type') == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
            <?php echo $this->session->flashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<body>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Profil Guru</h2>
            <div>
                <a href="<?php echo base_url('guru/profil/edit'); ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
            </div>
        </div>

        <!-- Profil Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <?php
                        $foto_profil = !empty($guru['foto_profil']) ? $guru['foto_profil'] : 'default.jpg';
                        // Cek apakah file ada di folder profiles
                        $foto_path = base_url('uploads/profiles/' . $foto_profil);
                        $file_exists = file_exists(FCPATH . 'uploads/profiles/' . $foto_profil);
                        
                        // Jika file tidak ada, gunakan default avatar
                        if (!$file_exists) {
                            $foto_path = base_url('assets/images/default-avatar.jpg');
                        }
                        ?>
                        <img src="<?php echo $foto_path; ?>" 
                             class="profile-img rounded-circle mb-3 shadow" 
                             alt="Foto Profil <?php echo $guru['nama']; ?>"
                             onerror="this.src='<?php echo base_url('assets/images/default-avatar.jpg'); ?>'">
                        <h4 class="mb-1"><?php echo $guru['nama']; ?></h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-user-tie"></i> Guru Freelance
                        </p>
                    </div>
                    <div class="col-md-9">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong><i class="fas fa-envelope text-primary"></i> Email:</strong><br>
                                    <?php echo $guru['email']; ?>
                                </p>
                                <p class="mb-2">
                                    <strong><i class="fas fa-phone text-primary"></i> Telepon:</strong><br>
                                    <?php echo $guru['telepon'] ?: '-'; ?>
                                </p>
                                <p class="mb-2">
                                    <strong><i class="fas fa-map-marker-alt text-primary"></i> Alamat:</strong><br>
                                    <?php echo $guru['alamat'] ?: '-'; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong><i class="fas fa-star text-warning"></i> Rating:</strong><br>
                                    <?php echo $guru['rating_rata2']; ?> ⭐
                                </p>
                                <p class="mb-2">
                                    <strong><i class="fas fa-check-circle text-primary"></i> Status:</strong><br>
                                    <span class="badge bg-<?php echo $guru['status_verifikasi'] == 'approved' ? 'success' : ($guru['status_verifikasi'] == 'pending' ? 'warning' : 'danger'); ?>">
                                        <?php echo ucfirst($guru['status_verifikasi']); ?>
                                    </span>
                                </p>
                                <p class="mb-2">
                                    <strong><i class="fas fa-calendar-alt text-primary"></i> Bergabung:</strong><br>
                                    <?php echo $guru['tanggal_daftar_formatted']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Keahlian -->
                        <h5 class="mt-4"><i class="fas fa-tools text-primary"></i> Keahlian:</h5>
                        <div class="mb-3">
                            <?php if (!empty($keahlian_array)): ?>
                                <?php foreach ($keahlian_array as $skill): ?>
                                    <span class="badge bg-primary me-1 mb-1"><?php echo $skill; ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">Belum ada keahlian</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pengalaman -->
                        <h5 class="mt-4"><i class="fas fa-briefcase text-primary"></i> Pengalaman:</h5>
                        <p class="mb-0"><?php echo $guru['pengalaman'] ?: 'Belum ada pengalaman'; ?></p>
                        
                        <!-- Portofolio Section (HANYA PDF) -->
                        <h5 class="mt-4"><i class="fas fa-file-pdf text-primary"></i> Portofolio:</h5>
                        <?php if (!empty($portofolio_file) && $portofolio_exists): ?>
                            <div class="mb-3">
                                <!-- Tampilkan PDF dalam modal atau new tab -->
                                <a href="<?php echo $portofolio_file; ?>" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm portofolio-btn me-2">
                                   <i class="fas fa-file-pdf"></i> Lihat Portofolio PDF
                                </a>
                                
                                <!-- Button untuk preview inline -->
                                <button type="button" 
                                        class="btn btn-outline-secondary btn-sm portofolio-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#pdfModal">
                                    <i class="fas fa-eye"></i> Preview PDF
                                </button>
                                
                                <!-- Modal untuk preview PDF -->
                                <div class="modal fade" id="pdfModal" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Portofolio: <?php echo $portofolio_name; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <iframe src="<?php echo $portofolio_file; ?>" 
                                                        width="100%" 
                                                        height="500px" 
                                                        style="border: none;">
                                                </iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="<?php echo $portofolio_file; ?>" 
                                                   class="btn btn-primary" 
                                                   download="<?php echo $portofolio_name; ?>">
                                                   <i class="fas fa-download"></i> Download PDF
                                                </a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <small class="text-muted d-block mt-1">File: <?php echo $portofolio_name; ?></small>
                            </div>
                        <?php elseif (!empty($portofolio_file) && !$portofolio_exists): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> File portofolio tidak ditemukan di server.
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Belum ada portofolio</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $statistik['total_kursus']; ?></div>
                    <div class="text-muted">
                        <i class="fas fa-book"></i> Kursus Aktif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $statistik['total_siswa']; ?></div>
                    <div class="text-muted">
                        <i class="fas fa-users"></i> Total Siswa
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $statistik['avg_rating']; ?></div>
                    <div class="text-muted">
                        <i class="fas fa-star"></i> Rating Rata-rata
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number">Rp <?php echo number_format($statistik['total_pendapatan'], 0, ',', '.'); ?></div>
                    <div class="text-muted">
                        <i class="fas fa-money-bill-wave"></i> Total Pendapatan
                    </div>
                </div>
            </div>
        </div>

        <!-- Kursus yang Diajar -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chalkboard-teacher text-primary"></i> Kursus yang Diajar</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($kursus)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Judul Kursus</th>
                                    <th>Kategori</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kursus as $k): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $k['judul_kursus']; ?></strong>
                                        </td>
                                        <td><span class="badge bg-secondary"><?php echo $k['nama_kategori']; ?></span></td>
                                        <td>Rp <?php echo number_format($k['biaya'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge <?php echo $k['status_kursus'] == 'active' ? 'bg-success' : ($k['status_kursus'] == 'pending' ? 'bg-warning' : 'bg-secondary'); ?>">
                                                <?php echo ucfirst($k['status_kursus']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($k['tanggal_dibuat'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">
                        <i class="fas fa-book fa-2x mb-3 d-block"></i>
                        Belum ada kursus yang diajar
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk auto-close alert setelah 5 detik
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
