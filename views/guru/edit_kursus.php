<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('guru/kursus/list_kursus') ?>">Manajemen Kursus</a></li>
                    <li class="breadcrumb-item active">Edit Kursus</li>
                </ol>
            </nav>

            <!-- Form -->
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Kursus</h4>
                            <p class="text-muted mb-0">ID: <?= $kursus['kursus_id'] ?> | Terakhir diperbarui: <?= date('d M Y H:i', strtotime($kursus['updated_at'] ?? $kursus['tanggal_dibuat'])) ?></p>
                        </div>
                        <div>
                            <a href="<?= base_url('guru/kursus/list_kursus') ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="<?= base_url('guru/kursus/update_kursus/' . $kursus['kursus_id']) ?>" method="post" enctype="multipart/form-data" id="editKursusForm">
                        
                        <!-- Gambar Kursus -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Kursus</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img src="<?= $kursus['gambar_url'] ?>" 
                                                 alt="Gambar Kursus" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; object-fit: cover;"
                                                 id="imagePreview">
                                        </div>
                                        <?php if ($kursus['gambar_kursus'] != 'default-course.jpg'): ?>
                                        <div class="mb-3">
                                            <a href="<?= base_url('guru/kursus/hapus_gambar/' . $kursus['kursus_id']) ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Hapus gambar kursus?')">
                                                <i class="fas fa-trash me-1"></i> Hapus Gambar
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        <div>
                                            <input type="file" name="gambar_kursus" id="gambar_kursus" 
                                                   class="form-control" accept="image/*" onchange="previewImage(this)">
                                            <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF (Max 2MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <!-- Informasi Dasar -->
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Judul Kursus <span class="text-danger">*</span></label>
                                        <input type="text" name="judul_kursus" class="form-control" 
                                               value="<?= htmlspecialchars($kursus['judul_kursus']) ?>" required
                                               placeholder="Masukkan judul kursus yang menarik" maxlength="150">
                                        <small class="text-muted">Maksimal 150 karakter</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select name="kategori_id" class="form-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php foreach ($kategori as $k): ?>
                                                <option value="<?= $k['kategori_id'] ?>" 
                                                    <?= ($k['kategori_id'] == $kursus['kategori_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($k['nama_kategori']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Level Kursus <span class="text-danger">*</span></label>
                                        <select name="level_kursus" class="form-select" required>
                                            <option value="pemula" <?= ($kursus['level_kursus'] == 'pemula') ? 'selected' : '' ?>>Pemula</option>
                                            <option value="menengah" <?= ($kursus['level_kursus'] == 'menengah') ? 'selected' : '' ?>>Menengah</option>
                                            <option value="mahir" <?= ($kursus['level_kursus'] == 'mahir') ? 'selected' : '' ?>>Mahir</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <h6 class="mb-3"><i class="fas fa-align-left me-2"></i>Deskripsi Kursus</h6>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Singkat <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control" rows="4" required
                                          placeholder="Jelaskan secara singkat tentang kursus ini"><?= htmlspecialchars($kursus['deskripsi']) ?></textarea>
                                <small class="text-muted">Berikan gambaran umum tentang kursus</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Detail Lengkap</label>
                                <textarea name="detail" class="form-control" rows="5"
                                          placeholder="Jelaskan secara detail materi yang akan diajarkan"><?= htmlspecialchars($kursus['detail'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Target Pembelajaran</label>
                                    <textarea name="target_pembelajaran" class="form-control" rows="3"
                                              placeholder="Apa yang akan dipelajari siswa?"><?= htmlspecialchars($kursus['target_pembelajaran'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prasyarat</label>
                                    <textarea name="prasyarat" class="form-control" rows="3"
                                              placeholder="Persyaratan yang harus dipenuhi siswa"><?= htmlspecialchars($kursus['prasyarat'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Biaya dan Durasi -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Biaya dan Durasi</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Biaya Kursus (Rp) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="biaya" class="form-control" required 
                                                   min="0" step="1000" value="<?= $kursus['biaya'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Durasi (Bulan) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="durasi" class="form-control" required 
                                                   min="1" max="24" value="<?= $kursus['durasi_bulan'] ?? 1 ?>">
                                            <span class="input-group-text">Bulan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Jadwal Kursus</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                        <input type="date" name="jadwal_mulai" class="form-control" required
                                               value="<?= isset($kursus['jadwal_mulai']) && $kursus['jadwal_mulai'] != '0000-00-00' ? $kursus['jadwal_mulai'] : date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                        <input type="date" name="jadwal_selesai" class="form-control" required
                                               value="<?= isset($kursus['jadwal_selesai']) && $kursus['jadwal_selesai'] != '0000-00-00' ? $kursus['jadwal_selesai'] : date('Y-m-d', strtotime('+1 month')) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Status Kursus <span class="text-danger">*</span></label>
                                <select name="status_kursus" class="form-select" required>
                                    <option value="aktif" <?= ($kursus['status_kursus'] == 'active') ? 'selected' : '' ?>>Aktif</option>
                                    <option value="nonaktif" <?= ($kursus['status_kursus'] == 'inactive') ? 'selected' : '' ?>>Nonaktif</option>
                                    <option value="pending" <?= ($kursus['status_kursus'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                </select>
                                <small class="text-muted">
                                    <?php if ($kursus['status_kursus'] == 'pending'): ?>
                                    Kursus dalam status pending menunggu verifikasi admin
                                    <?php else: ?>
                                    Pilih "Aktif" untuk menampilkan kursus kepada siswa
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between border-top pt-4">
                            <a href="<?= base_url('guru/kursus/list_kursus') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Perbarui Kursus
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(file);
    }
}

// Form validation
document.getElementById('editKursusForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('gambar_kursus');
    const maxSize = 2 * 1024 * 1024; // 2MB
    
    if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size;
        const fileType = fileInput.files[0].type;
        
        if (!fileType.match('image.*')) {
            e.preventDefault();
            alert('Hanya file gambar yang diperbolehkan!');
            return false;
        }
        
        if (fileSize > maxSize) {
            e.preventDefault();
            alert('Ukuran file maksimal 2MB!');
            return false;
        }
    }
    
    const jadwalMulai = document.querySelector('input[name="jadwal_mulai"]').value;
    const jadwalSelesai = document.querySelector('input[name="jadwal_selesai"]').value;
    
    if (jadwalSelesai <= jadwalMulai) {
        e.preventDefault();
        alert('Tanggal selesai harus setelah tanggal mulai!');
        return false;
    }
    
    return true;
});
</script>