<div class="container-fluid py-4">
    <!-- Judul Halaman -->
    <div class="d-flex align-items-center mb-4">
        <a href="<?= base_url('lpk/penilaian'); ?>" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Penilaian Tugas</h4>
            <p class="text-muted mb-0">Berikan penilaian untuk tugas siswa</p>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- KONTEN UTAMA -->
        <div class="col-lg-8">
            <!-- Informasi Tugas -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($pengumpulan['judul_tugas'] ?? ''); ?></h5>
                    <p class="text-muted mb-3"><?= htmlspecialchars($pengumpulan['judul_materi'] ?? ''); ?></p>
                    
                    <div class="mb-3">
                        <strong>Deskripsi Tugas:</strong>
                        <p class="mt-2"><?= nl2br(htmlspecialchars($pengumpulan['deskripsi_tugas'] ?? 'Tidak ada deskripsi')); ?></p>
                    </div>
                    
                    <?php if (!empty($pengumpulan['file_template'])): ?>
                    <div class="mb-3">
                        <strong>Template Tugas:</strong>
                        <div class="mt-2">
                            <a href="<?= base_url('uploads/templates/' . $pengumpulan['file_template']); ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Unduh Template
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- File Jawaban -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">File Jawaban Siswa</h5>
                    
                    <?php if (!empty($pengumpulan['file_tugas'])): ?>
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <span><?= basename($pengumpulan['file_tugas']); ?></span>
                            </div>
                            <a href="<?= base_url($pengumpulan['file_tugas']); ?>" 
                               class="btn btn-sm btn-primary" download>
                                <i class="fas fa-download me-1"></i> Unduh
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($pengumpulan['link_pengumpulan'])): ?>
                    <div class="alert alert-light border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-link text-primary me-2"></i>
                                <span>Link Pengumpulan</span>
                            </div>
                            <a href="<?= htmlspecialchars($pengumpulan['link_pengumpulan']); ?>" 
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i> Buka
                            </a>
                        </div>
                        <small class="text-muted d-block mt-2"><?= htmlspecialchars($pengumpulan['link_pengumpulan']); ?></small>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (empty($pengumpulan['file_tugas']) && empty($pengumpulan['link_pengumpulan'])): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Siswa tidak mengumpulkan file atau link
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Catatan Siswa -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Catatan dari Siswa</h5>
                    <div class="alert alert-info">
                        <?= nl2br(htmlspecialchars($pengumpulan['catatan_siswa'] ?? 'Tidak ada catatan dari siswa')); ?>
                    </div>
                </div>
            </div>

            <!-- Feedback Sebelumnya -->
            <?php if (!empty($pengumpulan['feedback']) && $pengumpulan['status'] == 'dinilai'): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Penilaian Sebelumnya</h5>
                    <div class="alert alert-success">
                        <div class="fw-bold">Nilai: <?= $pengumpulan['nilai']; ?>/100</div>
                        <div class="mt-2">
                            <strong>Feedback:</strong>
                            <p class="mt-1"><?= nl2br(htmlspecialchars($pengumpulan['feedback'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR KANAN -->
        <div class="col-lg-4">
            <!-- Informasi Pengumpulan -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informasi Pengumpulan</h5>
                    
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted" style="width: 40%">Siswa</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary me-2 d-flex align-items-center justify-content-center text-white"
                                         style="width:32px;height:32px;">
                                         <?= strtoupper(substr($pengumpulan['nama_siswa'] ?? '', 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($pengumpulan['nama_siswa'] ?? ''); ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($pengumpulan['email_siswa'] ?? ''); ?></small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kursus</td>
                            <td><?= htmlspecialchars($pengumpulan['judul_kursus'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Instruktur</td>
                            <td><?= htmlspecialchars($pengumpulan['nama_instruktur'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Kumpul</td>
                            <td><?= $tanggal_kumpul_formatted; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Deadline</td>
                            <td><?= $deadline_formatted; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                <?php if ($terlambat): ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-clock me-1"></i> Terlambat <?= abs($selisih_hari); ?> hari
                                </span>
                                <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i> Tepat Waktu
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Maks</td>
                            <td><?= $pengumpulan['max_score'] ?? 100; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Form Penilaian -->
            <?php if ($pengumpulan['status'] != 'dinilai'): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Form Penilaian LPK</h5>
                    
                    <?= form_open('lpk/penilaian/simpan_nilai', array('id' => 'form-nilai')); ?>
                        <input type="hidden" name="pengumpulan_id" value="<?= $pengumpulan['pengumpulan_id']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nilai (0-100)</label>
                            <input type="number" name="nilai" class="form-control" 
                                   min="0" max="100" step="0.1" required
                                   placeholder="Masukkan nilai"
                                   value="<?= $pengumpulan['nilai'] ?? ''; ?>">
                            <div class="form-text">Skala penilaian: 0-100 | Maksimal: <?= $pengumpulan['max_score'] ?? 100; ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Feedback untuk Siswa</label>
                            <textarea name="feedback" class="form-control" rows="5" 
                                      placeholder="Berikan feedback yang konstruktif untuk perkembangan siswa..."><?= $pengumpulan['feedback'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="notifikasi" class="form-check-input" id="notif" checked>
                            <label class="form-check-label" for="notif">
                                <i class="fas fa-bell me-1"></i> Kirim notifikasi ke siswa
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('lpk/penilaian'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Penilaian
                            </button>
                        </div>
                    <?= form_close(); ?>
                    
                    <hr class="my-3">
                    
                    <div class="text-center">
                        <?php if (!empty($pengumpulan['email_siswa'])): ?>
                        <a href="mailto:<?= htmlspecialchars($pengumpulan['email_siswa']); ?>" class="text-decoration-none">
                            <i class="fas fa-envelope me-1"></i> Kirim Email ke Siswa
                        </a>
                        <?php else: ?>
                        <span class="text-muted">Email siswa tidak tersedia</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle me-2"></i> Tugas Sudah Dinilai</h5>
                        <div class="mt-3">
                            <div class="fw-bold fs-4">Nilai: <?= $pengumpulan['nilai']; ?>/100</div>
                            <div class="mt-2">
                                <strong>Feedback yang diberikan:</strong>
                                <p class="mt-1"><?= nl2br(htmlspecialchars($pengumpulan['feedback'])); ?></p>
                            </div>
                            <small class="text-muted">Dinilai oleh LPK</small>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="<?= base_url('lpk/penilaian'); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.card-title {
    color: #333;
    font-weight: 600;
}
.badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}
.alert {
    border-radius: 8px;
}
.btn {
    border-radius: 6px;
    padding: 0.5rem 1rem;
}
.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}
.fw-semibold {
    font-weight: 600;
}
</style>

<script>
// Validasi client-side untuk form
document.getElementById('form-nilai')?.addEventListener('submit', function(e) {
    const nilaiInput = document.querySelector('input[name="nilai"]');
    const nilai = parseFloat(nilaiInput.value);
    const maxScore = <?= $pengumpulan['max_score'] ?? 100; ?>;
    
    if (nilai < 0 || nilai > maxScore) {
        e.preventDefault();
        alert('Nilai harus antara 0 dan ' + maxScore);
        nilaiInput.focus();
        return false;
    }
    
    if (isNaN(nilai)) {
        e.preventDefault();
        alert('Nilai harus berupa angka');
        nilaiInput.focus();
        return false;
    }
    
    // Konfirmasi sebelum submit
    if (!confirm('Apakah Anda yakin ingin menyimpan penilaian ini?')) {
        e.preventDefault();
        return false;
    }
    
    return true;
});

// Auto-save draft (opsional)
const nilaiField = document.querySelector('input[name="nilai"]');
const feedbackField = document.querySelector('textarea[name="feedback"]');

function saveDraft() {
    const draft = {
        nilai: nilaiField.value,
        feedback: feedbackField.value,
        pengumpulan_id: <?= $pengumpulan['pengumpulan_id']; ?>,
        timestamp: new Date().getTime()
    };
    localStorage.setItem('penilaian_draft_<?= $pengumpulan['pengumpulan_id']; ?>', JSON.stringify(draft));
}

// Load draft jika ada
window.addEventListener('load', function() {
    const savedDraft = localStorage.getItem('penilaian_draft_<?= $pengumpulan['pengumpulan_id']; ?>');
    if (savedDraft) {
        const draft = JSON.parse(savedDraft);
        if (confirm('Draft penilaian sebelumnya ditemukan. Apakah Anda ingin memuatnya?')) {
            nilaiField.value = draft.nilai || '';
            feedbackField.value = draft.feedback || '';
        }
    }
});

// Auto-save setiap 10 detik
setInterval(saveDraft, 10000);

// Clear draft saat form submit
document.getElementById('form-nilai')?.addEventListener('submit', function() {
    localStorage.removeItem('penilaian_draft_<?= $pengumpulan['pengumpulan_id']; ?>');
});
</script>