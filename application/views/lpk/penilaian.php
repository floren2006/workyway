<?php $this->load->view('lpk/sidebar'); ?>

<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Penilaian Tugas</h4>
                <p class="text-muted mb-0">Kelola penilaian tugas siswa</p>
            </div>
            <div>
                <a href="<?= base_url('penilaian_lpk/statistik'); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-chart-bar me-1"></i> Lihat Statistik
                </a>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Perlu Dinilai</h6>
                                <h3 class="fw-bold mb-0"><?= $perlu_dinilai; ?></h3>
                            </div>
                            <div class="icon-shape bg-primary text-white rounded-circle p-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Sudah Dinilai</h6>
                                <h3 class="fw-bold mb-0"><?= $sudah_dinilai; ?></h3>
                            </div>
                            <div class="icon-shape bg-success text-white rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Rata-rata Nilai</h6>
                                <h3 class="fw-bold mb-0"><?= $rata_rata; ?></h3>
                            </div>
                            <div class="icon-shape bg-info text-white rounded-circle p-3">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Tugas -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Daftar Tugas yang Dikumpulkan</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('lpk/penilaian'); ?>">Semua</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('lpk/penilaian_lpk?status=belum'); ?>">Belum Dinilai</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('lpk/penilaian_lpk?status=sudah'); ?>">Sudah Dinilai</a></li>
                        </ul>
                    </div>
                </div>

                <?php if (empty($penilaian)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5>Tidak ada tugas yang perlu dinilai</h5>
                        <p class="text-muted">Belum ada siswa yang mengumpulkan tugas</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Kursus</th>
                                    <th>Tugas</th>
                                    <th>Tanggal Kumpul</th>
                                    <th>Deadline</th>
                                    <th>Pengajar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penilaian as $p): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary me-2 d-flex align-items-center justify-content-center text-white"
                                                    style="width:32px;height:32px;">
                                                    <?= strtoupper(substr($p['nama_siswa'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= htmlspecialchars($p['nama_siswa']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($p['judul_kursus']); ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($p['judul_tugas']); ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($p['judul_materi']); ?></small>
                                        </td>
                                        <td>
                                            <?= date('d M Y', strtotime($p['tanggal_kumpul'])); ?>
                                            <small class="d-block text-muted"><?= date('H:i', strtotime($p['tanggal_kumpul'])); ?></small>
                                        </td>
                                        <td>
                                            <?= date('d M Y', strtotime($p['deadline'])); ?>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($p['nama_guru'] ?? 'N/A'); ?></small>
                                        </td>
                                        <td>
                                            <?php if ($p['status'] == 'dikumpulkan'): ?>
                                                <span class="badge bg-warning">Perlu Dinilai</span>
                                            <?php elseif ($p['status'] == 'dinilai'): ?>
                                                <span class="badge bg-success">Sudah Dinilai</span>
                                                <small class="d-block text-muted">Nilai: <?= $p['nilai']; ?>/100</small>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= ucfirst($p['status']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= base_url('lpk/penilaian/detail/' . $p['pengumpulan_id']); ?>" 
                                                class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($p['status'] == 'dikumpulkan'): ?>
                                                    <a href="<?= base_url('lpk/penilaian/detail/' . $p['pengumpulan_id']); ?>" 
                                                    class="btn btn-sm btn-primary">
                                                        <i class="fas fa-pen me-1"></i> Nilai
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.main-content {
    margin-left: 250px; /* sesuaikan dengan lebar sidebar */
    padding: 20px;
}
.card {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.icon-shape {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}
.table td {
    vertical-align: middle;
}
.badge {
    font-size: 0.75em;
    padding: 0.4em 0.8em;
}
</style>