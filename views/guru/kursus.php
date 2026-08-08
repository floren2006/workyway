<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Kursus Online</title>
   
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <style>
        .table-clean {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table-clean thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 12px 15px;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }
        .table-clean tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.9rem;
        }
        .table-clean tbody tr:hover {
            background-color: #f8f9fa;
        }

        .thumb {
            width: 50px;
            height: 30px;
            background-size: cover;
            background-position: center;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .thumb:hover {
            transform: scale(1.1);
        }

        .badge-soft {
            background-color: #e9ecef;
            color: #495057;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #842029;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .btn-group .btn {
            padding: 4px 6px;
            margin: 0 1px;
        }
        .action-icon {
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .action-icon:hover {
            transform: translateY(-2px);
        }

        .card-clean {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        .btn-add {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .upload-box {
            padding: 2rem;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .upload-box:hover {
            border-color: #4e73df;
            background-color: #f0f7ff;
        }
        .upload-icon {
            font-size: 2rem;
            color: #6c757d;
        }

        /* Modal styling */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .modal-title {
            font-size: 1rem;
            font-weight: 600;
        }
        .nav-tabs .nav-link {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
            color: #6c757d;
            border: none;
        }
        .nav-tabs .nav-link.active {
            color: #4e73df;
            border-bottom: 2px solid #4e73df;
            font-weight: 600;
        }
        .nav-tabs .nav-link:hover {
            color: #4e73df;
        }
        .form-control-sm {
            font-size: 0.85rem;
        }
        .form-control-sm:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        /* File name preview */
        .file-name {
            font-size: 0.8rem;
            font-weight: 500;
            color: #28a745;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.85rem;
            }
            .btn-group .btn {
                padding: 3px 5px;
                margin: 0 1px;
            }
            .thumb {
                width: 40px;
                height: 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php $this->load->view('templates/guru_header'); ?>

    <div class="container-fluid py-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Manajemen Kursus</h2>
            <a href="<?= base_url('guru/kursus/tambah') ?>" class="btn btn-primary btn-add">
                <i class="fas fa-plus me-1"></i> Tambah Kursus
            </a>
        </div>


        <!-- FLASH MESSAGES -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
       
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- CARD TABLE -->
        <div class="card card-clean">
            <div class="card-body p-0">

                <?php if (empty($kursus)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <p>Belum ada kursus</p>
                    </div>
                <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-clean mb-0">
                        <thead>
                            <tr>
                                <th>Judul Kursus</th>
                                <th>Kategori Kursus</th>
                                <th>Siswa</th>
                                <th>Durasi</th>
                                <th>Biaya</th>
                                <th>Gambar</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kursus as $k):
                                // Parse durasi untuk mendapatkan angka bulan
                                $durasi_text = $k['durasi'];
                                $durasi_match = preg_match('/(\d+)/', $durasi_text, $matches);
                                $durasi = $durasi_match ? $matches[1] : 1;
                               
                                // Status
                                $status = $k['status_kursus'] ?? 'active';
                                $status_label = $status === 'active' ? 'Aktif' : ($status === 'inactive' ? 'Nonaktif' : 'Pending');
                                $status_class = $status === 'active' ? 'status-active' : ($status === 'inactive' ? 'status-inactive' : 'status-pending');
                            ?>
                            <tr>
                                <!-- Judul Kursus -->
                                <td class="fw-medium">
                                    <div class="fw-semibold"><?= htmlspecialchars($k['judul_kursus']) ?></div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d M Y', strtotime($k['tanggal_dibuat'])) ?>
                                    </small>
                                </td>
                               
                                <!-- Kategori Kursus -->
                                <td>
                                    <span class="badge badge-soft">
                                        <?= htmlspecialchars($k['nama_kategori'] ?? '-') ?>
                                    </span>
                                </td>
                               
                                <!-- Siswa -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <span><?= $k['jumlah_siswa'] ?? 0 ?></span>
                                    </div>
                                </td>
                               
                                <!-- Durasi -->
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= $durasi ?> Bulan
                                    </span>
                                </td>
                               
                                <!-- Biaya -->
                                <td>
                                    <span class="fw-bold text-success">
                                        Rp <?= number_format($k['biaya'], 0, ',', '.') ?>
                                    </span>
                                </td>
                               
                                <!-- Gambar -->
                                <td>
                                    <div class="thumb"
                                         style="background-image:url('<?= $k['gambar_url'] ?>')"
                                         data-bs-toggle="tooltip"
                                         title="<?= htmlspecialchars($k['judul_kursus']) ?>">
                                    </div>
                                </td>
                               
                                <!-- Status -->
                                <td>
                                    <span class="status-pill <?= $status_class ?>">
                                        <?= $status_label ?>
                                    </span>
                                </td>
                               
                                <!-- Aksi -->
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- Detail Button -->
                                        <button type="button" class="btn btn-sm btn-outline-primary action-icon detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetail<?= $k['kursus_id'] ?>"
                                                title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                       
                                        <!-- Materi Button -->
                                        <button type="button" class="btn btn-sm btn-outline-info action-icon materi"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalMateri<?= $k['kursus_id'] ?>"
                                                title="Upload Materi">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                       
                                        <!-- Edit Button -->
                                        <a href="<?= base_url('guru/kursus/edit/'.$k['kursus_id']) ?>"
                                           class="btn btn-sm btn-outline-warning action-icon edit"
                                           title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                       
                                        <!-- Delete Button -->
                                        <a href="<?= base_url('guru/kursus/hapus/'.$k['kursus_id']) ?>"
                                           class="btn btn-sm btn-outline-danger action-icon delete"
                                           onclick="return confirm('Hapus kursus <?= addslashes($k['judul_kursus']) ?>?')"
                                           title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL DETAIL -->
                            <div class="modal fade" id="modalDetail<?= $k['kursus_id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Kursus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="kursus-detail-image"
                                                         style="width: 100%; height: 120px; background-image: url('<?= $k['gambar_url'] ?>'); background-size: cover; background-position: center; border-radius: 6px;">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6><?= htmlspecialchars($k['judul_kursus']) ?></h6>
                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-tag me-1"></i> <?= htmlspecialchars($k['nama_kategori'] ?? 'Tidak ada kategori') ?>
                                                    </p>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="badge bg-<?= $status === 'active' ? 'success' : ($status === 'inactive' ? 'secondary' : 'warning') ?> me-2">
                                                            <?= $status_label ?>
                                                        </span>
                                                        <span class="badge bg-info text-white me-2">
                                                            <?= $durasi ?> Bulan
                                                        </span>
                                                        <span class="badge bg-success text-white">
                                                            Rp <?= number_format($k['biaya'], 0, ',', '.') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group list-group-flush">
                                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span><i class="fas fa-users me-2 text-primary"></i> Siswa</span>
                                                            <span class="fw-bold"><?= $k['jumlah_siswa'] ?? 0 ?></span>
                                                        </div>
                                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span><i class="fas fa-comments me-2 text-info"></i> Diskusi</span>
                                                            <span class="fw-bold"><?= $k['jumlah_forum'] ?? 0 ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <?php if (!empty($k['deskripsi'])): ?>
                                            <hr>
                                            <div class="mb-3">
                                                <h6><i class="fas fa-align-left me-2"></i>Deskripsi</h6>
                                                <p class="mb-0" style="font-size: 0.9rem;"><?= nl2br(htmlspecialchars($k['deskripsi'])) ?></p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <a href="<?= base_url('guru/kursus/edit/' . $k['kursus_id']) ?>" class="btn btn-primary">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL UPLOAD MATERI -->
                            <div class="modal fade" id="modalMateri<?= $k['kursus_id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Materi Kursus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted mb-3">
                                                Upload untuk kursus:<br>
                                                <strong><?= htmlspecialchars($k['judul_kursus']) ?></strong>
                                            </p>
                                           
                                            <!-- Tab Navigation -->
                                            <ul class="nav nav-tabs mb-3" id="materiTab<?= $k['kursus_id'] ?>" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="video-tab<?= $k['kursus_id'] ?>" data-bs-toggle="tab" data-bs-target="#video<?= $k['kursus_id'] ?>" type="button">
                                                        Video
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="materi-tab<?= $k['kursus_id'] ?>" data-bs-toggle="tab" data-bs-target="#materi<?= $k['kursus_id'] ?>" type="button">
                                                        Materi
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="tugas-tab<?= $k['kursus_id'] ?>" data-bs-toggle="tab" data-bs-target="#tugas<?= $k['kursus_id'] ?>" type="button">
                                                        Tugas
                                                    </button>
                                                </li>
                                            </ul>
                                           
                                            <!-- Tab Content -->
                                            <div class="tab-content" id="materiTabContent<?= $k['kursus_id'] ?>">
                                                <!-- Video Tab -->
                                                <div class="tab-pane fade show active" id="video<?= $k['kursus_id'] ?>" role="tabpanel">
                                                    <form action="<?= base_url('guru/kursus/upload_video/' . $k['kursus_id']) ?>" method="post" enctype="multipart/form-data">
                                                        <div class="mb-3">
                                                            <label class="form-label">Judul Video</label>
                                                            <input type="text" name="judul_video" class="form-control form-control-sm" placeholder="Masukkan judul video" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Upload Video</label>
                                                            <div class="input-group">
                                                                <input type="file" name="file_video" class="form-control form-control-sm" accept="video/*" required>
                                                            </div>
                                                            <small class="text-muted">Format: MP4, AVI, MOV, WMV, FLV, MKV (Max: 100MB)</small>
                                                        </div>
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-upload me-1"></i> Upload Video
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                               
                                                <!-- Materi Tab -->
                                                <div class="tab-pane fade" id="materi<?= $k['kursus_id'] ?>" role="tabpanel">
                                                    <form action="<?= base_url('guru/kursus/upload_materi/' . $k['kursus_id']) ?>" method="post" enctype="multipart/form-data">
                                                        <div class="mb-3">
                                                            <label class="form-label">Judul Materi</label>
                                                            <input type="text" name="judul_materi" class="form-control form-control-sm" placeholder="Masukkan judul materi" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Upload Materi</label>
                                                            <div class="input-group">
                                                                <input type="file" name="file_materi" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx" required>
                                                            </div>
                                                            <small class="text-muted">Format: PDF, DOC, DOCX, TXT, PPT, PPTX, XLS, XLSX (Max: 10MB)</small>
                                                        </div>
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-upload me-1"></i> Upload Materi
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                               
                                                <!-- Tugas Tab (Menggantikan Kuis) -->
                                                <div class="tab-pane fade" id="tugas<?= $k['kursus_id'] ?>" role="tabpanel">
                                                    <form action="<?= base_url('guru/kursus/buat_tugas/' . $k['kursus_id']) ?>" method="post" enctype="multipart/form-data" id="formTugas<?= $k['kursus_id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Pilih Materi <span class="text-danger">*</span></label>
                                                            <select name="materi_id" class="form-control form-control-sm" required id="selectMateri<?= $k['kursus_id'] ?>">
                                                                <option value="">-- Pilih Materi --</option>
                                                                <!-- Materi akan di-load via AJAX -->
                                                            </select>
                                                            <div class="form-text">Pilih materi untuk menambahkan tugas</div>
                                                        </div>
                                                       
                                                        <div class="mb-3">
                                                            <label class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                                                            <input type="text" name="judul_tugas" class="form-control form-control-sm" placeholder="Masukkan judul tugas" required>
                                                        </div>
                                                       
                                                        <div class="mb-3">
                                                            <label class="form-label">Deskripsi Tugas</label>
                                                            <textarea name="deskripsi_tugas" class="form-control form-control-sm" rows="3" placeholder="Deskripsi tugas (opsional)"></textarea>
                                                        </div>
                                                       
                                                        <div class="mb-3">
                                                            <label class="form-label">Upload File Soal (Opsional)</label>
                                                            <input type="file" name="file_tugas" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.txt,.zip,.rar">
                                                            <div class="form-text">Format: PDF, DOC, DOCX, TXT, ZIP, RAR (Max: 10MB)</div>
                                                        </div>
                                                       
                                                        <div class="row mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label">Batas Waktu</label>
                                                                <input type="datetime-local" name="deadline" class="form-control form-control-sm" value="<?= date('Y-m-d\TH:i', strtotime('+1 week')) ?>">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label">Nilai Maksimal</label>
                                                                <input type="number" name="max_score" class="form-control form-control-sm" min="1" max="1000" value="100">
                                                            </div>
                                                        </div>
                                                       
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-save me-1"></i> Buat Tugas
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php endif; ?>
            </div>
        </div>

        <!-- UPLOAD MATERI (GLOBAL) -->
        <div class="card card-clean mt-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Upload Materi Kursus</h6>
               
                <!-- Kursus Selection -->
                <div class="mb-3">
                    <label class="form-label">Pilih Kursus</label>
                    <select class="form-select form-select-sm" id="selectKursus">
                        <option value="">-- Pilih Kursus --</option>
                        <?php foreach ($kursus as $k): ?>
                        <option value="<?= $k['kursus_id'] ?>"><?= htmlspecialchars($k['judul_kursus']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="upload-box text-center">
                    <i class="fas fa-upload upload-icon mb-2"></i>
                    <p class="text-muted mb-3">Upload video, materi, atau tugas</p>
                    <button class="btn btn-primary btn-sm px-4" id="btnPilihFile">
                        Pilih File
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL UPLOAD GLOBAL -->
    <div class="modal fade" id="modalUploadGlobal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Materi Kursus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3" id="selectedKursusTitle">
                        Pilih kursus terlebih dahulu
                    </p>
                   
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" id="materiTabGlobal" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="video-tab-global" data-bs-toggle="tab" data-bs-target="#videoGlobal" type="button">
                                Video
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="materi-tab-global" data-bs-toggle="tab" data-bs-target="#materiGlobal" type="button">
                                Materi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tugas-tab-global" data-bs-toggle="tab" data-bs-target="#tugasGlobal" type="button">
                                Tugas
                            </button>
                        </li>
                    </ul>
                   
                    <!-- Tab Content -->
                    <div class="tab-content" id="materiTabContentGlobal">
                        <!-- Video Tab -->
                        <div class="tab-pane fade show active" id="videoGlobal" role="tabpanel">
                            <form id="formVideoGlobal" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Judul Video</label>
                                    <input type="text" name="judul_video" class="form-control form-control-sm" placeholder="Masukkan judul video" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Upload Video</label>
                                    <div class="input-group">
                                        <input type="file" name="file_video" class="form-control form-control-sm" accept="video/*" required>
                                    </div>
                                    <small class="text-muted">Format: MP4, AVI, MOV, WMV, FLV, MKV (Max: 100MB)</small>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload me-1"></i> Upload Video
                                    </button>
                                </div>
                            </form>
                        </div>
                       
                        <!-- Materi Tab -->
                        <div class="tab-pane fade" id="materiGlobal" role="tabpanel">
                            <form id="formMateriGlobal" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Judul Materi</label>
                                    <input type="text" name="judul_materi" class="form-control form-control-sm" placeholder="Masukkan judul materi" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Upload Materi</label>
                                    <div class="input-group">
                                        <input type="file" name="file_materi" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx" required>
                                    </div>
                                    <small class="text-muted">Format: PDF, DOC, DOCX, TXT, PPT, PPTX, XLS, XLSX (Max: 10MB)</small>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload me-1"></i> Upload Materi
                                    </button>
                                </div>
                            </form>
                        </div>
                       
                        <!-- Tugas Tab (Menggantikan Kuis) -->
                        <div class="tab-pane fade" id="tugasGlobal" role="tabpanel">
                            <form id="formTugasGlobal" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Materi <span class="text-danger">*</span></label>
                                    <select name="materi_id" class="form-control form-control-sm" required id="materiSelectGlobal">
                                        <option value="">-- Pilih Kursus terlebih dahulu --</option>
                                    </select>
                                </div>
                               
                                <div class="mb-3">
                                    <label class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                                    <input type="text" name="judul_tugas" class="form-control form-control-sm" placeholder="Masukkan judul tugas" required>
                                </div>
                               
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Tugas</label>
                                    <textarea name="deskripsi_tugas" class="form-control form-control-sm" rows="2" placeholder="Deskripsi tugas (opsional)"></textarea>
                                </div>
                               
                                <div class="mb-3">
                                    <label class="form-label">Upload File Soal (Opsional)</label>
                                    <input type="file" name="file_tugas" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.txt,.zip,.rar">
                                    <div class="form-text">Format: PDF, DOC, DOCX, TXT, ZIP, RAR (Max: 10MB)</div>
                                </div>
                               
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Batas Waktu</label>
                                        <input type="datetime-local" name="deadline" class="form-control form-control-sm" value="<?= date('Y-m-d\TH:i', strtotime('+1 week')) ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Nilai Maksimal</label>
                                        <input type="number" name="max_score" class="form-control form-control-sm" min="1" max="1000" value="100">
                                    </div>
                                </div>
                               
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save me-1"></i> Buat Tugas
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
    <script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
       
        // Global Upload Button
        const btnPilihFile = document.getElementById('btnPilihFile');
        const selectKursus = document.getElementById('selectKursus');
        const modalUploadGlobal = new bootstrap.Modal(document.getElementById('modalUploadGlobal'));
        const selectedKursusTitle = document.getElementById('selectedKursusTitle');
        const formVideoGlobal = document.getElementById('formVideoGlobal');
        const formMateriGlobal = document.getElementById('formMateriGlobal');
        const formTugasGlobal = document.getElementById('formTugasGlobal');
        const materiSelectGlobal = document.getElementById('materiSelectGlobal');
       
        // Map kursus data for title lookup
        const kursusData = <?= json_encode(array_column($kursus, 'judul_kursus', 'kursus_id')) ?>;
       
        btnPilihFile.addEventListener('click', function() {
            const kursusId = selectKursus.value;
           
            if (!kursusId) {
                alert('Silakan pilih kursus terlebih dahulu!');
                return;
            }
           
            // Update modal title
            const kursusTitle = kursusData[kursusId] || 'Kursus Terpilih';
            selectedKursusTitle.innerHTML = `Upload untuk kursus:<br><strong>${kursusTitle}</strong>`;
           
            // Update form actions
            formVideoGlobal.action = `<?= base_url('guru/kursus/upload_video/') ?>${kursusId}`;
            formMateriGlobal.action = `<?= base_url('guru/kursus/upload_materi/') ?>${kursusId}`;
            formTugasGlobal.action = `<?= base_url('guru/kursus/buat_tugas/') ?>${kursusId}`;
           
            // Reset forms
            formVideoGlobal.reset();
            formMateriGlobal.reset();
            formTugasGlobal.reset();
           
            // Reset file name displays
            document.querySelectorAll('.file-name-display').forEach(el => el.remove());
           
            // Load materi untuk dropdown tugas
            loadMateriForGlobalTugas(kursusId);
           
            // Show modal
            modalUploadGlobal.show();
        });
       
        // Function to load materi for global tugas modal
        function loadMateriForGlobalTugas(kursusId) {
            // Clear existing options except first
            while (materiSelectGlobal.options.length > 1) {
                materiSelectGlobal.remove(1);
            }
           
            // Show loading
            const loadingOption = document.createElement('option');
            loadingOption.value = '';
            loadingOption.text = 'Memuat materi...';
            loadingOption.disabled = true;
            materiSelectGlobal.appendChild(loadingOption);
           
            // AJAX request
            fetch(`<?= base_url('guru/kursus/get_materi_by_kursus/') ?>${kursusId}`)
                .then(response => response.json())
                .then(data => {
                    // Remove loading option
                    materiSelectGlobal.remove(materiSelectGlobal.options.length - 1);
                   
                    if (data.error) {
                        const errorOption = document.createElement('option');
                        errorOption.value = '';
                        errorOption.text = 'Error: ' + data.error;
                        materiSelectGlobal.appendChild(errorOption);
                        return;
                    }
                   
                    if (data.length === 0) {
                        const noDataOption = document.createElement('option');
                        noDataOption.value = '';
                        noDataOption.text = 'Belum ada materi';
                        materiSelectGlobal.appendChild(noDataOption);
                        return;
                    }
                   
                    // Add materi options
                    data.forEach(materi => {
                        const option = document.createElement('option');
                        option.value = materi.materi_id;
                        option.text = materi.judul_materi + ' (' + materi.tipe_materi + ')';
                        materiSelectGlobal.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    materiSelectGlobal.remove(materiSelectGlobal.options.length - 1);
                   
                    const errorOption = document.createElement('option');
                    errorOption.value = '';
                    errorOption.text = 'Gagal memuat materi';
                    materiSelectGlobal.appendChild(errorOption);
                });
        }
       
        // Function to load materi when tugas modal is opened (per kursus)
        function loadMateriForTugas(kursusId) {
            const selectElement = document.getElementById('selectMateri' + kursusId);
            if (!selectElement) return;
           
            // Clear existing options except first
            while (selectElement.options.length > 1) {
                selectElement.remove(1);
            }
           
            // Show loading
            const loadingOption = document.createElement('option');
            loadingOption.value = '';
            loadingOption.text = 'Memuat materi...';
            loadingOption.disabled = true;
            selectElement.appendChild(loadingOption);
           
            // AJAX request
            fetch(`<?= base_url('guru/kursus/get_materi_by_kursus/') ?>${kursusId}`)
                .then(response => response.json())
                .then(data => {
                    // Remove loading option
                    selectElement.remove(selectElement.options.length - 1);
                   
                    if (data.error) {
                        const errorOption = document.createElement('option');
                        errorOption.value = '';
                        errorOption.text = 'Error: ' + data.error;
                        selectElement.appendChild(errorOption);
                        return;
                    }
                   
                    if (data.length === 0) {
                        const noDataOption = document.createElement('option');
                        noDataOption.value = '';
                        noDataOption.text = 'Belum ada materi';
                        selectElement.appendChild(noDataOption);
                        return;
                    }
                   
                    // Add materi options
                    data.forEach(materi => {
                        const option = document.createElement('option');
                        option.value = materi.materi_id;
                        option.text = materi.judul_materi + ' (' + materi.tipe_materi + ')';
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    selectElement.remove(selectElement.options.length - 1);
                   
                    const errorOption = document.createElement('option');
                    errorOption.value = '';
                    errorOption.text = 'Gagal memuat materi';
                    selectElement.appendChild(errorOption);
                });
        }
       
        // Event listener untuk modal tugas per kursus
        const tugasModals = document.querySelectorAll('[id^="modalMateri"]');
        tugasModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function(event) {
                const modalId = event.target.id;
                const kursusId = modalId.replace('modalMateri', '');
               
                // Load materi untuk dropdown
                loadMateriForTugas(kursusId);
            });
        });
       
        // File input preview
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name;
                const parentDiv = this.closest('.mb-3');
               
                if (fileName && parentDiv) {
                    // Remove existing file name display
                    const existingSpan = parentDiv.querySelector('.file-name-display');
                    if (existingSpan) {
                        existingSpan.remove();
                    }
                   
                    // Add new file name display
                    const fileSize = (this.files[0].size / (1024 * 1024)).toFixed(2);
                    const span = document.createElement('span');
                    span.className = 'file-name-display text-success d-block mt-1 small';
                    span.innerHTML = `<i class="fas fa-file me-1"></i> ${fileName} (${fileSize} MB)`;
                    parentDiv.appendChild(span);
                }
            });
        });
       
        // Form validation for tugas
        document.querySelectorAll('[id^="formTugas"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                // Basic validation
                const judulTugas = this.querySelector('[name="judul_tugas"]').value.trim();
                const materiId = this.querySelector('[name="materi_id"]').value;
               
                if (!judulTugas || !materiId) {
                    e.preventDefault();
                    alert('Harap isi judul tugas dan pilih materi!');
                    return false;
                }
               
                // File size validation
                const fileInput = this.querySelector('[name="file_tugas"]');
                if (fileInput.files.length > 0) {
                    const fileSize = fileInput.files[0].size;
                    const maxSize = 10 * 1024 * 1024; // 10MB
                   
                    if (fileSize > maxSize) {
                        e.preventDefault();
                        alert('Ukuran file maksimal 10MB!');
                        return false;
                    }
                }
               
                return true;
            });
        });
       
        // Set minimum date for deadline inputs
        const now = new Date().toISOString().slice(0, 16);
        document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
            input.min = now;
        });
    });
    </script>
</body>
</html>
