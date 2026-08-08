

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Dashboard Guru</h2>
    <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-primary btn-sm">
        Logout
    </a>
</div>


<!-- Statistik -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-label">Kursus Aktif</div>
            <div class="stat-number">
                <?php echo $statistik['total_kursus'] ?? 0; ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-number">
                <?php echo $statistik['total_siswa'] ?? 0; ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-label">Rating</div>
            <div class="stat-number">
                <?php echo $statistik['avg_rating'] ?? 0; ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-label">Pendapatan</div>
            <div class="stat-number">
                Rp <?php echo number_format($statistik['pendapatan'] ?? 0, 0, ',', '.'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Konten Bawah -->
<div class="row">
    <!-- Kursus Terpopuler -->
    <div class="col-md-6">
        <div class="stat-card">
            <h5 class="fw-bold mb-3">Kursus Terpopuler</h5>
            <?php if (!empty($kursus_populer)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($kursus_populer as $k): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo $k['judul_kursus']; ?></span>
                            <small class="text-muted"><?php echo $k['jumlah_siswa']; ?> siswa</small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Belum ada data</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Aktivitas -->
    <div class="col-md-6">
        <div class="stat-card">
            <h5 class="fw-bold mb-3">Aktivitas Terbaru</h5>
            <?php if (!empty($aktivitas)): ?>
                <?php foreach ($aktivitas as $a): ?>
                    <div class="activity-item">
                        <?php echo $a['deskripsi']; ?><br>
                        <small class="text-muted"><?php echo $a['tanggal']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Tidak ada aktivitas</p>
            <?php endif; ?>
        </div>
    </div>
</div>


