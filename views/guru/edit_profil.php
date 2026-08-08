<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Kursus Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 3px solid #dee2e6;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
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
            <h2 class="fw-bold mb-0">Edit Profil Guru</h2>
            <div>
                <a href="<?php echo base_url('guru/profil'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Profil
                </a>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo base_url('guru/profil/edit'); ?>" enctype="multipart/form-data">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle text-primary"></i> Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control" 
                                   value="<?php echo htmlspecialchars($guru['nama']); ?>" required>
                            <div class="form-text">Nama lengkap Anda yang akan ditampilkan</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($guru['email']); ?>" required>
                            <div class="form-text">Email aktif untuk notifikasi</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" 
                                   value="<?php echo htmlspecialchars($guru['telepon'] ?: ''); ?>">
                            <div class="form-text">Nomor telepon yang bisa dihubungi</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"><?php echo htmlspecialchars($guru['alamat'] ?: ''); ?></textarea>
                            <div class="form-text">Alamat lengkap tempat tinggal</div>
                        </div>
                        
                        <!-- Foto Profil -->
                        <div class="mb-4">
                            <label class="form-label">Foto Profil</label>
                            <?php 
                            $foto_profil = !empty($guru['foto_profil']) ? $guru['foto_profil'] : 'uploads/profiles/default.jpg';
                            $foto_path = base_url('uploads/profiles/' . $foto_profil);
                            $file_exists = file_exists(FCPATH . 'uploads/profiles/' . $foto_profil);
                            
                            if ($file_exists && $foto_profil !== 'uploads/profiles/default.jpg'): 
                            ?>
                                <div class="mb-3">
                                    <img src="<?php echo $foto_path; ?>" 
                                         alt="Foto Profil Saat Ini" 
                                         class="profile-preview rounded-circle mb-2"
                                         onerror="this.src='<?php echo base_url('assets/images/default-avatar.jpg'); ?>'">
                                    <br>
                                    <small class="text-muted">File: <?php echo $foto_profil; ?></small>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapusFoto">
                                    <label class="form-check-label text-danger" for="hapusFoto">
                                        <i class="fas fa-trash-alt"></i> Hapus foto profil (gunakan default)
                                    </label>
                                </div>
                                
                                <p class="text-muted">Upload foto baru untuk mengganti:</p>
                            <?php else: ?>
                                <div class="mb-3">
                                    <img src="<?php echo base_url('assets/images/default-avatar.jpg'); ?>" 
                                         alt="Foto Default" 
                                         class="profile-preview rounded-circle mb-2">
                                    <br>
                                    <small class="text-muted">Foto profil saat ini menggunakan default</small>
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" name="foto_profil" class="form-control" 
                                   accept="image/*" id="fotoInput">
                            <div class="form-text">
                                Format: JPG, JPEG, PNG, GIF (maks. 2MB)
                                <br>
                                <small id="fileInfo"></small>
                            </div>
                            
                            <!-- Preview foto baru -->
                            <div id="previewContainer" class="mt-3" style="display: none;">
                                <p class="mb-1"><strong>Preview Foto Baru:</strong></p>
                                <img id="previewImage" class="profile-preview rounded-circle">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-briefcase text-primary"></i> Informasi Profesional</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Keahlian (pisahkan dengan koma)</label>
                        <input type="text" name="keahlian" class="form-control" 
                               value="<?php echo htmlspecialchars($keahlian_str); ?>"
                               placeholder="Contoh: Web Development, Mobile App, Database">
                        <div class="form-text">Sebutkan keahlian utama Anda dipisahkan dengan koma</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pengalaman</label>
                        <textarea name="pengalaman" class="form-control" rows="5" 
                                  placeholder="Deskripsikan pengalaman kerja atau mengajar Anda"><?php echo htmlspecialchars($guru['pengalaman'] ?: ''); ?></textarea>
                        <div class="form-text">Jelaskan pengalaman profesional Anda secara lengkap</div>
                    </div>
                    
                    <!-- Portofolio Section (HANYA PDF) -->
                    <div class="mb-3">
                        <label class="form-label">Portofolio (PDF)</label>
                        <?php if (!empty($portofolio_file_name)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> File portofolio saat ini: 
                                <strong><?php echo $portofolio_file_name; ?></strong>
                                <br>
                                <small>File tersimpan di: uploads/portofolio/</small>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="hapus_portofolio" value="1" id="hapusPortofolio">
                                <label class="form-check-label text-danger" for="hapusPortofolio">
                                    <i class="fas fa-trash-alt"></i> Hapus portofolio saat ini
                                </label>
                            </div>
                            
                            <p class="text-muted">Upload file PDF baru untuk mengganti portofolio:</p>
                        <?php endif; ?>
                        
                        <input type="file" name="portofolio_file" class="form-control mb-2" 
                               accept=".pdf" id="portofolioInput">
                        <div class="form-text">
                            Format: PDF (maks. 10MB)
                            <br>
                            <small id="portofolioInfo"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="reset" class="btn btn-outline-secondary btn-lg me-2">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview foto sebelum upload
        const fotoInput = document.getElementById('fotoInput');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const fileInfo = document.getElementById('fileInfo');
        
        fotoInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                // Validasi ukuran
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimum 2MB.');
                    this.value = '';
                    previewContainer.style.display = 'none';
                    fileInfo.textContent = '';
                    return;
                }
                
                // Validasi tipe
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Harap upload gambar JPG, PNG, atau GIF.');
                    this.value = '';
                    previewContainer.style.display = 'none';
                    fileInfo.textContent = '';
                    return;
                }
                
                // Tampilkan info file
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.textContent = `Ukuran: ${fileSize} MB | Tipe: ${file.type}`;
                fileInfo.style.color = 'green';
                
                // Tampilkan preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                fileInfo.textContent = '';
            }
        });
        
        // Validasi portofolio
        const portofolioInput = document.getElementById('portofolioInput');
        const portofolioInfo = document.getElementById('portofolioInfo');
        
        portofolioInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                // Validasi ukuran
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimum 10MB.');
                    this.value = '';
                    portofolioInfo.textContent = '';
                    return;
                }
                
                // Validasi tipe (hanya PDF)
                if (file.type !== 'application/pdf') {
                    alert('Format file harus PDF.');
                    this.value = '';
                    portofolioInfo.textContent = '';
                    return;
                }
                
                // Tampilkan info file
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                portofolioInfo.textContent = `Ukuran: ${fileSize} MB | Tipe: PDF`;
                portofolioInfo.style.color = 'green';
            } else {
                portofolioInfo.textContent = '';
            }
        });
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            let hasError = false;
            let errorMessage = '';
            
            // Validasi foto profil
            if (fotoInput.files.length > 0) {
                const foto = fotoInput.files[0];
                const allowedFotoTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                const maxFotoSize = 2 * 1024 * 1024; // 2MB
                
                if (!allowedFotoTypes.includes(foto.type)) {
                    errorMessage += 'Format foto tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.\n';
                    hasError = true;
                }
                
                if (foto.size > maxFotoSize) {
                    errorMessage += 'Ukuran foto terlalu besar. Maksimum 2MB.\n';
                    hasError = true;
                }
            }
            
            // Validasi portofolio
            const portofolioInput = document.querySelector('input[name="portofolio_file"]');
            const hapusPortofolioCheckbox = document.querySelector('input[name="hapus_portofolio"]');
            const allowedPortofolioTypes = ['application/pdf'];
            const maxPortofolioSize = 10 * 1024 * 1024; // 10MB
            
            if (!hapusPortofolioCheckbox || !hapusPortofolioCheckbox.checked) {
                if (portofolioInput.files.length > 0) {
                    const portofolio = portofolioInput.files[0];
                    
                    if (!allowedPortofolioTypes.includes(portofolio.type)) {
                        errorMessage += 'Format portofolio harus PDF.\n';
                        hasError = true;
                    }
                    
                    if (portofolio.size > maxPortofolioSize) {
                        errorMessage += 'Ukuran portofolio terlalu besar. Maksimum 10MB.\n';
                        hasError = true;
                    }
                }
            }
            
            if (hasError) {
                alert('Ada kesalahan:\n\n' + errorMessage);
                event.preventDefault();
            } else {
                // Tampilkan loading
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;
                
                // Biarkan form submit berjalan
            }
        });
    });
    </script>
</body>
</html>