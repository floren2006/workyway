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
    <!-- Custom CSS -->
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
        }
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 25px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        .required:after {
            content: " *";
            color: #dc3545;
        }
        .image-preview {
            max-width: 300px;
            max-height: 200px;
            object-fit: cover;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 5px;
        }
        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .breadcrumb {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }
        .section-title {
            color: #667eea;
            font-weight: 600;
            border-left: 4px solid #667eea;
            padding-left: 15px;
            margin-bottom: 20px;
        }
        .char-count {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: right;
        }
        .char-count.warning {
            color: #ffc107;
        }
        .char-count.danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php $this->load->view('templates/guru_header'); ?>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>"><i class="bi bi-house-door"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/kursus/list_kursus') ?>"><i class="bi bi-book"></i> Kursus Saya</a></li>
                        <li class="breadcrumb-item active"><i class="bi bi-plus-circle"></i> Tambah Kursus</li>
                    </ol>
                </nav>

                <!-- Alert Messages -->
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Kursus Baru</h4>
                                <p class="mb-0 opacity-75">Isi formulir di bawah ini dengan lengkap dan benar</p>
                            </div>
                            <div class="badge bg-light text-dark p-2">
                                <i class="bi bi-info-circle me-1"></i> Semua field bertanda * wajib diisi
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="<?= base_url('guru/kursus/simpan_kursus') ?>" method="post" enctype="multipart/form-data" id="kursusForm" novalidate>
                            
                            <!-- Section 1: Informasi Dasar -->
                            <div class="mb-5">
                                <h5 class="section-title"><i class="bi bi-info-circle me-2"></i>Informasi Dasar Kursus</h5>
                                
                                <div class="row">
                                    <!-- Judul Kursus -->
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label required">Judul Kursus</label>
                                        <input type="text" name="judul_kursus" class="form-control" 
                                               placeholder="Contoh: Pemrograman Web Fullstack untuk Pemula"
                                               value="<?= set_value('judul_kursus') ?>"
                                               required
                                               minlength="5"
                                               maxlength="150">
                                        <div class="form-text">Buat judul yang menarik dan deskriptif (5-150 karakter)</div>
                                        <div class="char-count" id="judulCount">0/150</div>
                                    </div>
                                    
                                    <!-- Kategori -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Kategori</label>
                                        <select name="kategori_id" class="form-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php foreach ($kategori as $k): ?>
                                                <option value="<?= $k['kategori_id'] ?>" 
                                                    <?= set_select('kategori_id', $k['kategori_id']) ?>>
                                                    <?= htmlspecialchars($k['nama_kategori']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text">Pilih kategori yang paling sesuai</div>
                                    </div>
                                </div>
                                
                                <!-- Gambar Kursus -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gambar Kursus</label>
                                        <input type="file" name="gambar_kursus" class="form-control" 
                                               accept="image/*" 
                                               id="gambarInput">
                                        <div class="form-text">
                                            Ukuran maksimal: 2MB. Format: JPG, PNG, GIF, WebP.<br>
                                            Rekomendasi ukuran: 800x450px. Kosongkan untuk menggunakan gambar default.
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div id="imagePreview" class="text-center" style="display: none;">
                                            <img id="previewImage" src="#" alt="Preview" class="image-preview">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                                    <i class="bi bi-trash"></i> Hapus Gambar
                                                </button>
                                            </div>
                                        </div>
                                        <div id="defaultPreview" class="text-center">
                                            <img src="<?= base_url('assets/images/default-course.jpg') ?>" 
                                                 alt="Default" class="image-preview">
                                            <div class="mt-2 text-muted">Gambar default</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 2: Deskripsi -->
                            <div class="mb-5">
                                <h5 class="section-title"><i class="bi bi-text-paragraph me-2"></i>Deskripsi Kursus</h5>
                                
                                <!-- Deskripsi Singkat -->
                                <div class="mb-4">
                                    <label class="form-label required">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" 
                                              placeholder="Jelaskan secara singkat dan menarik tentang kursus ini..."
                                              required minlength="20" maxlength="500"><?= set_value('deskripsi') ?></textarea>
                                    <div class="form-text">Deskripsi yang menarik akan meningkatkan minat siswa (20-500 karakter)</div>
                                    <div class="char-count" id="deskripsiCount">0/500</div>
                                </div>
                                
                                <!-- Detail Lengkap -->
                                <div class="mb-3">
                                    <label class="form-label required">Detail Lengkap</label>
                                    <textarea name="detail" class="form-control" rows="8" 
                                              placeholder="Jelaskan secara detail isi kursus ini:
• Materi yang akan diajarkan
• Tujuan pembelajaran
• Siapa yang cocok mengikuti kursus ini
• Keunggulan kursus Anda
• Proyek yang akan dikerjakan
• dll." 
                                              required minlength="30"><?= set_value('detail') ?></textarea>
                                    <div class="form-text">
                                        Gunakan poin-poin (•) untuk menjelaskan materi secara terstruktur.<br>
                                        Jelaskan dengan detail agar siswa memahami apa yang akan mereka pelajari.
                                    </div>
                                    <div class="char-count" id="detailCount">0 karakter</div>
                                </div>
                            </div>
                            
                            <!-- Section 3: Biaya & Durasi -->
                            <div class="mb-5">
                                <h5 class="section-title"><i class="bi bi-currency-dollar me-2"></i>Biaya & Durasi</h5>
                                
                                <div class="row">
                                    <!-- Biaya -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Biaya Kursus (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="biaya" class="form-control" 
                                                   placeholder="500000"
                                                   value="<?= set_value('biaya') ?>"
                                                   required
                                                   min="0"
                                                   step="1000">
                                        </div>
                                        <div class="form-text">Contoh: 500000 untuk Rp 500.000</div>
                                    </div>
                                    
                                    <!-- Durasi -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Durasi Kursus</label>
                                        <select name="durasi" class="form-select" required>
                                            <option value="">-- Pilih Durasi --</option>
                                            <option value="1 Bulan" <?= set_select('durasi', '1 Bulan') ?>>1 Bulan</option>
                                            <option value="1.5 Bulan" <?= set_select('durasi', '1.5 Bulan') ?>>1.5 Bulan</option>
                                            <option value="2 Bulan" <?= set_select('durasi', '2 Bulan') ?>>2 Bulan</option>
                                            <option value="2.5 Bulan" <?= set_select('durasi', '2.5 Bulan') ?>>2.5 Bulan</option>
                                            <option value="3 Bulan" <?= set_select('durasi', '3 Bulan') ?>>3 Bulan</option>
                                            <option value="4 Bulan" <?= set_select('durasi', '4 Bulan') ?>>4 Bulan</option>
                                            <option value="6 Bulan" <?= set_select('durasi', '6 Bulan') ?>>6 Bulan</option>
                                        </select>
                                        <div class="form-text">Perkiraan waktu yang dibutuhkan untuk menyelesaikan kursus</div>
                                    </div>
                                </div>
                                
                                <!-- Informasi Tambahan -->
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Informasi Penting:</strong><br>
                                    1. Biaya kursus sudah termasuk akses materi, video pembelajaran, dan sertifikat<br>
                                    2. Durasi adalah perkiraan waktu yang dibutuhkan untuk menyelesaikan semua materi<br>
                                    3. Anda dapat mengubah informasi ini nanti melalui menu edit kursus
                                </div>
                            </div>
                            
                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-between pt-3 border-top">
                                <a href="<?= base_url('guru/kursus/list_kursus') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-check-circle me-1"></i> Simpan Kursus
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    // DOM Ready
    $(document).ready(function() {
        // Character counters
        updateCharCount('judul_kursus', 'judulCount', 150);
        updateCharCount('deskripsi', 'deskripsiCount', 500);
        updateCharCount('detail', 'detailCount');
        
        // Real-time character counting
        $('input[name="judul_kursus"], textarea[name="deskripsi"], textarea[name="detail"]').on('input', function() {
            const name = $(this).attr('name');
            const counterId = name + 'Count';
            const maxLength = $(this).attr('maxlength');
            updateCharCount(name, counterId, maxLength);
        });
        
        // Image preview
        $('#gambarInput').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                    $('#imagePreview').show();
                    $('#defaultPreview').hide();
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Form validation
        $('#kursusForm').submit(function(e) {
            let isValid = true;
            let errorMessages = [];
            
            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            
            // Check required fields
            $('[required]').each(function() {
                if (!$(this).val().trim()) {
                    const fieldName = $(this).attr('name');
                    const label = $(this).closest('.mb-3').find('label').text().replace(' *', '');
                    errorMessages.push(`${label} harus diisi`);
                    $(this).addClass('is-invalid');
                    isValid = false;
                }
            });
            
            // Check min length
            const judul = $('input[name="judul_kursus"]').val().trim();
            if (judul.length < 5) {
                errorMessages.push('Judul kursus minimal 5 karakter');
                $('input[name="judul_kursus"]').addClass('is-invalid');
                isValid = false;
            }
            
            const deskripsi = $('textarea[name="deskripsi"]').val().trim();
            if (deskripsi.length < 20) {
                errorMessages.push('Deskripsi minimal 20 karakter');
                $('textarea[name="deskripsi"]').addClass('is-invalid');
                isValid = false;
            }
            
            const detail = $('textarea[name="detail"]').val().trim();
            if (detail.length < 30) {
                errorMessages.push('Detail kursus minimal 30 karakter');
                $('textarea[name="detail"]').addClass('is-invalid');
                isValid = false;
            }
            
            // Check biaya
            const biaya = $('input[name="biaya"]').val();
            if (biaya < 0) {
                errorMessages.push('Biaya tidak boleh negatif');
                $('input[name="biaya"]').addClass('is-invalid');
                isValid = false;
            }
            
            // Check file size if image is selected
            const gambarInput = $('#gambarInput')[0];
            if (gambarInput.files.length > 0) {
                const fileSize = gambarInput.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    errorMessages.push('Ukuran gambar maksimal 2MB');
                    $('#gambarInput').addClass('is-invalid');
                    isValid = false;
                }
                
                // Check file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(gambarInput.files[0].type)) {
                    errorMessages.push('Format gambar harus JPG, PNG, GIF, atau WebP');
                    $('#gambarInput').addClass('is-invalid');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Show error alert
                let errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                errorHtml += '<i class="bi bi-exclamation-triangle me-2"></i>';
                errorHtml += '<strong>Mohon perbaiki kesalahan berikut:</strong><ul class="mb-0 mt-2">';
                errorMessages.forEach(msg => {
                    errorHtml += `<li>${msg}</li>`;
                });
                errorHtml += '</ul>';
                errorHtml += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                errorHtml += '</div>';
                
                // Remove existing alert if any
                $('.alert-danger').remove();
                
                // Add new alert at the top of form
                $(errorHtml).insertBefore('.card-header');
                
                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.alert-danger').offset().top - 100
                }, 500);
            } else {
                // Disable submit button to prevent double submission
                $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
            }
        });
        
        // Function to update character count
        function updateCharCount(fieldName, counterId, maxLength = null) {
            const text = $(`[name="${fieldName}"]`).val();
            const count = text.length;
            
            let counterText = `${count}`;
            if (maxLength) {
                counterText += `/${maxLength}`;
                
                // Add warning/danger class based on length
                const $counter = $('#' + counterId);
                $counter.removeClass('warning danger');
                
                if (count > maxLength * 0.8) {
                    $counter.addClass('warning');
                }
                if (count > maxLength) {
                    $counter.addClass('danger');
                }
            } else {
                counterText += ' karakter';
            }
            
            $('#' + counterId).text(counterText);
        }
    });
    
    // Remove image function
    function removeImage() {
        $('#gambarInput').val('');
        $('#imagePreview').hide();
        $('#defaultPreview').show();
    }
    
    // Image preview without jQuery
    document.getElementById('gambarInput').addEventListener('change', function(e) {
        if (!this.files.length) return;
        
        const file = this.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('defaultPreview').style.display = 'none';
        }
        
        reader.readAsDataURL(file);
    });
    </script>
</body>
</html>