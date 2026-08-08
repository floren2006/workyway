<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tugas Baru - LPK System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: #1e293b;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }
        
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
        }
        
        .navbar-main {
            position: sticky;
            top: 0;
            z-index: 999;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .required:after {
            content: " *";
            color: red;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                margin-left: -280px;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .navbar-toggler {
                display: block !important;
            }
        }
        
        @media (min-width: 993px) {
            .navbar-toggler {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Wrapper -->
    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php $this->load->view('lpk/sidebar'); ?>
        </div>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg navbar-light border-bottom">
                <div class="container-fluid">
                    <!-- Toggle Button for Mobile -->
                    <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <h4 class="mb-0 d-none d-lg-block">Buat Tugas Baru</h4>
                    <h5 class="mb-0 d-lg-none">Buat Tugas</h5>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex align-items-center">
                        <a href="<?= base_url('lpk/tugas'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> <span class="d-none d-md-inline">Kembali</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="container-fluid py-4">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('lpk/dashboard'); ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('lpk/tugas'); ?>">Tugas</a></li>
                                <li class="breadcrumb-item active">Buat Tugas Baru</li>
                            </ol>
                        </nav>

                        <div class="card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i> Form Tambah Tugas</h5>
                                    <p class="text-muted mb-0">Isi form berikut untuk menambahkan tugas baru</p>
                                </div>
                                <div class="badge bg-primary">
                                    <i class="fas fa-tasks me-1"></i> Baru
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <!-- Alert Messages -->
                                <?php if($this->session->flashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?= $this->session->flashdata('success'); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if($this->session->flashdata('error')): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?= $this->session->flashdata('error'); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if(validation_errors()): ?>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Perhatikan kesalahan berikut:</strong>
                                        <ul class="mb-0 mt-2">
                                            <?= validation_errors('<li>', '</li>'); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <form action="<?= base_url('lpk/tugas/store'); ?>" method="POST" enctype="multipart/form-data">
                                    <!-- CSRF Protection -->
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                    <div class="row">
                                        <!-- Judul Tugas -->
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label required">
                                                <i class="fas fa-heading me-1"></i> Judul Tugas
                                            </label>
                                            <input type="text" name="judul_tugas" class="form-control" 
                                                   value="<?= set_value('judul_tugas'); ?>" required
                                                   placeholder="Contoh: Membuat Halaman Web Sederhana">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i> Maksimal 255 karakter
                                            </small>
                                        </div>

                                        <!-- Materi -->
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label required">
                                                <i class="fas fa-book me-1"></i> Materi
                                            </label>
                                            <select name="materi_id" class="form-select" required>
                                                <option value="">Pilih Materi...</option>
                                                <?php if(empty($materi)): ?>
                                                    <option value="" disabled>Tidak ada materi tersedia</option>
                                                <?php else: ?>
                                                    <?php foreach($materi as $m): ?>
                                                        <option value="<?= $m['materi_id']; ?>" 
                                                            <?= set_select('materi_id', $m['materi_id']); ?>>
                                                            <?= htmlspecialchars($m['judul_materi']); ?>
                                                            <?php if(isset($m['kursus_nama'])): ?>
                                                                 - <small class="text-muted"><?= htmlspecialchars($m['kursus_nama']); ?></small>
                                                            <?php endif; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i> Pilih materi yang sesuai dengan tugas
                                            </small>
                                        </div>

                                        <!-- Tipe Tugas -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label required">
                                                <i class="fas fa-users me-1"></i> Tipe Tugas
                                            </label>
                                            <select name="tipe_tugas" class="form-select" required>
                                                <option value="">Pilih Tipe...</option>
                                                <?php foreach($tipe_tugas_options as $value => $label): ?>
                                                    <option value="<?= $value; ?>" 
                                                        <?= set_select('tipe_tugas', $value); ?>>
                                                        <i class="fas fa-<?= $value == 'individual' ? 'user' : 'users'; ?> me-2"></i>
                                                        <?= $label; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Max Score -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label required">
                                                <i class="fas fa-star me-1"></i> Nilai Maksimal
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="max_score" class="form-control" 
                                                       value="<?= set_value('max_score', '100'); ?>" min="1" max="100" required>
                                                <span class="input-group-text">/100</span>
                                            </div>
                                            <small class="text-muted">Nilai maksimal yang bisa didapat siswa</small>
                                        </div>

                                        <!-- Deskripsi -->
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label required">
                                                <i class="fas fa-align-left me-1"></i> Deskripsi Tugas
                                            </label>
                                            <textarea name="deskripsi" class="form-control" rows="5" required
                                                      placeholder="Jelaskan secara detail tentang tugas yang akan diberikan kepada siswa..."><?= set_value('deskripsi'); ?></textarea>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i> Jelaskan tujuan, langkah-langkah, dan kriteria penilaian
                                            </small>
                                        </div>

                                        <!-- Deadline -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label required">
                                                <i class="fas fa-calendar-times me-1"></i> Deadline
                                            </label>
                                            <input type="datetime-local" name="deadline" class="form-control" 
                                                   value="<?= set_value('deadline'); ?>" required>
                                            <small class="text-muted">Batas waktu pengumpulan tugas</small>
                                        </div>

                                        <!-- File Template -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-file-archive me-1"></i> File Template (Opsional)
                                            </label>
                                            <div class="input-group">
                                                <input type="file" name="file_template" class="form-control"
                                                       accept=".zip,.rar,.pdf,.doc,.docx">
                                                <button type="button" class="btn btn-outline-secondary" 
                                                        data-bs-toggle="tooltip" title="Maksimal 50MB">
                                                    <i class="fas fa-question-circle"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i> ZIP, RAR, PDF, DOC (Maks. 50MB)
                                            </small>
                                        </div>

                                        <!-- Tombol Submit -->
                                        <div class="col-md-12 mt-4 pt-3 border-top">
                                            <div class="d-flex justify-content-between">
                                                <a href="<?= base_url('lpk/tugas'); ?>" class="btn btn-outline-secondary">
                                                    <i class="fas fa-times me-1"></i> Batal
                                                </a>
                                                <button type="submit" class="btn btn-primary px-4">
                                                    <i class="fas fa-save me-1"></i> Simpan Tugas
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Card Footer -->
                            <div class="card-footer bg-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-lightbulb text-success me-2"></i> Tips:</h6>
                                        <ul class="mb-0">
                                            <li>Gunakan judul yang jelas dan deskriptif</li>
                                            <li>Beri deadline yang realistis</li>
                                            <li>Sertakan instruksi yang jelas</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-exclamation-triangle text-warning me-2"></i> Perhatian:</h6>
                                        <ul class="mb-0">
                                            <li>Tugas yang sudah dibuat tidak dapat diubah tipe</li>
                                            <li>Pastikan deadline sudah benar</li>
                                            <li>Verifikasi semua data sebelum menyimpan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="footer mt-5 py-3 border-top bg-white">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span class="text-muted">
                                <i class="fas fa-copyright"></i> <?= date('Y'); ?> LPK System
                            </span>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="text-muted">
                                <i class="fas fa-user me-1"></i> 
                                <?= $this->session->userdata('nama') ?? 'Administrator'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle for Mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    if (window.innerWidth < 993) {
                        if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
            }
            
            // Set minimum datetime for deadline
            const now = new Date();
            const minDateTime = now.toISOString().slice(0, 16);
            const deadlineInput = document.querySelector('input[name="deadline"]');
            
            if (deadlineInput) {
                deadlineInput.min = minDateTime;
                
                // If no value, set default to tomorrow
                if (!deadlineInput.value) {
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    tomorrow.setHours(23, 59, 0);
                    deadlineInput.value = tomorrow.toISOString().slice(0, 16);
                }
            }
            
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth >= 993 && sidebar) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>