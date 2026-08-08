<div class="container-fluid mb-4">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Penilaian Tugas</h2>
        <div class="d-flex gap-2 align-items-center">
            <!-- Filter Dropdown -->
            <?php if (!empty($kursus_list)): ?>
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-1"></i> Filter Kursus
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('guru/penilaian'); ?>">Semua Kursus</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <?php foreach ($kursus_list as $kursus): ?>
                    <li>
                        <a class="dropdown-item" href="<?= base_url('guru/penilaian/filter/' . $kursus['kursus_id']); ?>">
                            <?= htmlspecialchars($kursus['judul_kursus']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash Messages -->
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

    <!-- STAT CARD -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Perlu Dinilai</small>
                    <h3 class="fw-bold text-warning mt-2"><?= $perlu_dinilai; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Sudah Dinilai</small>
                    <h3 class="fw-bold text-success mt-2"><?= $sudah_dinilai; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Rata-rata Nilai</small>
                    <h3 class="fw-bold text-primary mt-2"><?= $rata_rata; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE PENILAIAN -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (!empty($penilaian)): ?>
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
                                <!-- SISWA -->
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

                                <!-- KURSUS -->
                                <td>
                                    <?= htmlspecialchars($row['judul_kursus'] ?? 'Tidak ada data'); ?>
                                </td>

                                <!-- TUGAS -->
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($row['judul_tugas'] ?? 'Tidak ada data'); ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($row['judul_materi'] ?? ''); ?></small>
                                </td>

                                <!-- DISERAHKAN -->
                                <td class="text-muted">
                                    <?= isset($row['tanggal_kumpul']) ? date('d M Y H:i', strtotime($row['tanggal_kumpul'])) : 'N/A'; ?>
                                </td>

                                <!-- STATUS -->
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
                                    <?php elseif ($status == 'dikumpulkan' && $nilai !== null): ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-check me-1"></i> Dikumpulkan
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times me-1"></i> Belum Dikumpulkan
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- AKSI -->
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <?php if (($status == 'dikumpulkan' && $nilai === null) || $status == 'dinilai'): ?>
                                            <a href="<?= base_url('guru/penilaian/detail/' . $row['pengumpulan_id']); ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>
                                            <?php if ($row['file_tugas']): ?>
                                                <a href="<?= base_url('guru/penilaian/download_file/' . $row['pengumpulan_id']); ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="Download File">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-file-alt fa-3x mb-3"></i><br>
                    <h5>Tidak ada tugas yang perlu dinilai</h5>
                    <p class="mb-0">Belum ada siswa yang mengumpulkan tugas untuk kursus Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>