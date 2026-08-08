<div class="container-fluid mb-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Daftar Siswa</h2>
        <a href="<?php echo base_url('guru/dashboard'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- STATISTIK -->
    <?php if (!empty($siswa)): ?>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-number"><?= $statistik['total_siswa'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <div class="stat-label">Total Pendaftaran</div>
                <div class="stat-number"><?= $statistik['total_enrollment'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-label">Tugas Dikumpulkan</div>
                <div class="stat-number"><?= $statistik['total_tugas'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-label">Forum Diskusi</div>
                <div class="stat-number"><?= $statistik['total_forum'] ?? 0; ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kursus yang Diikuti</th>
                        <th>Tanggal Daftar</th>
                        <th>Jumlah Kursus</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (!empty($siswa)): ?>
                        <?php foreach ($siswa as $s): ?>
                            <tr>
                                <!-- NAMA SISWA -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary me-3 d-flex align-items-center justify-content-center text-white"
                                             style="width:40px;height:40px;">
                                             <?php 
                                                $nama = $s['nama'] ?? '';
                                                $initial = $nama ? strtoupper(substr($nama, 0, 1)) : '?';
                                                echo $initial;
                                             ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($s['nama'] ?? 'Tidak ada nama'); ?></div>
                                            <small class="text-muted d-block">
                                                <?= htmlspecialchars($s['email'] ?? 'Tidak ada email'); ?>
                                            </small>
                                            <?php if (!empty($s['pendidikan_terakhir'])): ?>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($s['pendidikan_terakhir']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- KURSUS YANG DIIKUTI -->
                                <td>
                                    <div class="kursus-list">
                                        <?php 
                                        $kursus_list = $s['kursus_diikuti'] ?? '';
                                        if (!empty($kursus_list)) {
                                            $kursus_array = explode(', ', $kursus_list);
                                            foreach ($kursus_array as $kursus) {
                                                echo '<span class="badge bg-secondary mb-1 me-1">' . htmlspecialchars(trim($kursus)) . '</span>';
                                            }
                                        } else {
                                            echo '<span class="text-muted">Belum ada kursus</span>';
                                        }
                                        ?>
                                    </div>
                                </td>

                                <!-- TANGGAL DAFTAR TERAKHIR -->
                                <td>
                                    <?php if (!empty($s['tanggal_daftar_terakhir'])): ?>
                                        <?= date('d M Y', strtotime($s['tanggal_daftar_terakhir'])); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- JUMLAH KURSUS -->
                                <td>
                                    <span class="badge bg-success"><?= $s['jumlah_kursus'] ?? 0; ?></span>
                                </td>

                                <!-- RATING -->
                                <td>
                                    <?php if ($s['rating_rata2'] > 0): ?>
                                        <div class="rating-stars">
                                            <?php 
                                            $rating = round($s['rating_rata2']);
                                            for ($i = 1; $i <= 5; $i++):
                                                if ($i <= $rating):
                                            ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-muted"></i>
                                            <?php endif; endfor; ?>
                                            <small class="text-muted ms-1">(<?= round($s['rating_rata2'], 1) ?>)</small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada rating</span>
                                    <?php endif; ?>
                                </td>

                                <!-- AKSI -->
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $s['siswa_id'] ?>">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>

                            </tr>

                            <!-- MODAL DETAIL -->
                            <div class="modal fade" id="modalDetail<?= $s['siswa_id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Siswa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-4">
                                                <div class="rounded-circle bg-primary mx-auto d-flex align-items-center justify-content-center text-white"
                                                     style="width:80px;height:80px;">
                                                     <?= strtoupper(substr($s['nama'], 0, 1)); ?>
                                                </div>
                                                <h5 class="mt-3"><?= htmlspecialchars($s['nama']); ?></h5>
                                                <p class="text-muted"><?= htmlspecialchars($s['email']); ?></p>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <strong><i class="fas fa-phone text-success me-2"></i> Telepon:</strong>
                                                    <p class="mb-0"><?= htmlspecialchars($s['telepon'] ?? '-'); ?></p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong><i class="fas fa-graduation-cap text-primary me-2"></i> Pendidikan:</strong>
                                                    <p class="mb-0"><?= htmlspecialchars($s['pendidikan_terakhir'] ?? '-'); ?></p>
                                                </div>
                                                <?php if (!empty($s['tanggal_lahir'])): ?>
                                                <div class="col-md-6 mb-3">
                                                    <strong><i class="fas fa-birthday-cake text-warning me-2"></i> Tanggal Lahir:</strong>
                                                    <p class="mb-0"><?= date('d M Y', strtotime($s['tanggal_lahir'])); ?></p>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="mb-3">
                                                <strong><i class="fas fa-book text-info me-2"></i> Kursus yang Diikuti:</strong>
                                                <div class="mt-2">
                                                    <?php 
                                                    if (!empty($kursus_list)) {
                                                        $kursus_array = explode(', ', $kursus_list);
                                                        foreach ($kursus_array as $kursus) {
                                                            echo '<span class="badge bg-info mb-1 me-1">' . htmlspecialchars(trim($kursus)) . '</span>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h6 class="text-muted">Total Kursus</h6>
                                                            <h3 class="text-primary"><?= $s['jumlah_kursus'] ?? 0; ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h6 class="text-muted">Rating</h6>
                                                            <h3 class="text-warning"><?= round($s['rating_rata2'] ?? 0, 1); ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <a href="mailto:<?= htmlspecialchars($s['email']); ?>" class="btn btn-primary">
                                                <i class="fas fa-envelope me-1"></i> Kirim Email
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-users-slash fa-2x mb-3"></i><br>
                                Belum ada siswa terdaftar di kursus Anda
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div>

</div>

<style>
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    font-size: 1.5rem;
}
.stat-icon.blue { background: #e3f2fd; color: #2196f3; }
.stat-icon.green { background: #e8f5e9; color: #4caf50; }
.stat-icon.purple { background: #f3e5f5; color: #9c27b0; }
.stat-icon.orange { background: #fff3e0; color: #ff9800; }
.stat-label {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 5px;
}
.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
}
.kursus-list .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    display: inline-block;
    margin-bottom: 2px;
}
.rating-stars {
    font-size: 0.9rem;
}
</style>

<!-- Tambahkan Bootstrap JS jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>