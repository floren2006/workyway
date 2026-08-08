<div class="container-fluid mb-4">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Penilaian Tugas - Filter</h2>
        <a href="<?= base_url('penilaian'); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Filter Options -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Filter Berdasarkan Kursus</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="list-group">
                        <a href="<?= base_url('penilaian/filter'); ?>" 
                           class="list-group-item list-group-item-action <?= !$selected_kursus ? 'active' : ''; ?>">
                            <i class="fas fa-list me-2"></i> Semua Kursus
                        </a>
                        <?php foreach ($kursus_list as $kursus): ?>
                            <a href="<?= base_url('penilaian/filter/' . $kursus['kursus_id']); ?>" 
                               class="list-group-item list-group-item-action <?= $selected_kursus == $kursus['kursus_id'] ? 'active' : ''; ?>">
                                <i class="fas fa-book me-2"></i> <?= htmlspecialchars($kursus['judul_kursus']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STAT CARD -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Perlu Dinilai</small>
                    <h3 class="fw-bold text-warning mt-2">
                        <?= $perlu_dinilai; ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Sudah Dinilai</small>
                    <h3 class="fw-bold text-success mt-2">
                        <?= $sudah_dinilai; ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Rata-rata Nilai</small>
                    <h3 class="fw-bold text-primary mt-2">
                        <?= $rata_rata; ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE PENILAIAN -->
    <?php if (!empty($penilaian)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <?= $selected_kursus ? 'Tugas untuk Kursus Terpilih' : 'Semua Tugas'; ?>
                    <span class="badge bg-primary ms-2"><?= count($penilaian); ?> tugas</span>
                </h5>
                
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="text-muted">
                            <tr>
                                <th>Siswa</th>
                                <th>Kursus</th>
                                <th>Tugas</th>
                                <th>Diserahkan</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($penilaian as $row): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary me-3 d-flex align-items-center justify-content-center text-white"
                                                 style="width:40px;height:40px;">
                                                 <?php 
                                                    $nama = $row['nama_siswa'] ?? 'Siswa';
                                                    $inisial = !empty($nama) ? strtoupper(substr($nama, 0, 1)) : '?';
                                                    echo $inisial; 
                                                 ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($row['nama_siswa'] ?? 'Tidak ada nama'); ?></div>
                                                <small class="text-muted">ID: <?= $row['siswa_id'] ?? 'N/A'; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['judul_kursus'] ?? 'Tidak ada data'); ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($row['judul_tugas'] ?? 'Tidak ada data'); ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($row['judul_materi'] ?? ''); ?></small>
                                    </td>
                                    <td class="text-muted">
                                        <?= isset($row['tanggal_kumpul']) ? date('d M Y H:i', strtotime($row['tanggal_kumpul'])) : 'N/A'; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = $row['status'] ?? 'belum_dikumpulkan';
                                        $nilai = $row['nilai'] ?? null;
                                        
                                        if ($status == 'dikumpulkan' && $nilai === null): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i> Perlu Dinilai
                                            </span>
                                        <?php elseif ($status == 'dinilai' && $nilai !== null): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i> Sudah Dinilai
                                            </span>
                                            <br>
                                            <small class="text-success">Nilai: <?= $nilai; ?>/100</small>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times me-1"></i> Belum Dikumpulkan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if (($status == 'dikumpulkan' && $nilai === null) || $status == 'dinilai'): ?>
                                            <a href="<?= base_url('penilaian/detail/' . $row['pengumpulan_id']); ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada tugas untuk kursus ini</h5>
                <p class="text-muted">Belum ada siswa yang mengumpulkan tugas untuk kursus yang dipilih.</p>
            </div>
        </div>
    <?php endif; ?>
</div>