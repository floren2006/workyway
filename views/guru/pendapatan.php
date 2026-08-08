<div class="container-fluid mb-4">

        <h2 class="fw-bold">Pendapatan</h2>
        <p class="text-muted">
            <?php if (!empty($guru['nama'])): ?>
                <i class="fas fa-user-graduate me-1"></i> <?= $guru['nama']; ?> 
                | Guru ID: <?= $guru_id; ?>
            <?php else: ?>
                <i class="fas fa-chalkboard-teacher me-1"></i> Guru ID: <?= $guru_id; ?>
            <?php endif; ?>
        </p>

    <!-- STAT CARD -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Pendapatan</small>
                            <h4 class="fw-bold mt-2">
                                Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>
                            </h4>
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i> Semua transaksi sukses
                            </small>
                        </div>
                        <div class="icon-circle bg-success">
                            <i class="fas fa-wallet text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Komisi Bulan Ini</small>
                            <h4 class="fw-bold mt-2">
                                Rp <?= number_format($komisi_bulan_ini, 0, ',', '.'); ?>
                            </h4>
                            <small class="text-info">
                                <i class="fas fa-calendar me-1"></i> <?= date('F Y'); ?>
                            </small>
                        </div>
                        <div class="icon-circle bg-info">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Pending / Menunggu</small>
                            <h4 class="fw-bold mt-2">
                                Rp <?= number_format($pending, 0, ',', '.'); ?>
                            </h4>
                            <small class="text-warning">
                                <i class="fas fa-clock me-1"></i> Menunggu konfirmasi
                            </small>
                        </div>
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-hourglass-half text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIWAYAT TRANSAKSI -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Riwayat Transaksi</h6>
                <div>
                    <a href="<?= base_url('pendapatan'); ?>" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-muted">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Kursus</th>
                            <th>Siswa</th>
                            <th>Total</th>
                            <th>Komisi</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transaksi)): ?>
                            <?php foreach ($transaksi as $row): ?>
                                <tr>
                                    <td>
                                        <small class="text-muted">#<?= $row['transaksi_id']; ?></small>
                                    </td>
                                    <td>
                                        <small><?= date('d M Y', strtotime($row['tanggal_transaksi'])); ?></small><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($row['tanggal_transaksi'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-medium" title="<?= $row['judul_kursus']; ?>">
                                            <?= strlen($row['judul_kursus']) > 25 ? substr($row['judul_kursus'], 0, 25) . '...' : $row['judul_kursus']; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle-sm bg-primary text-white me-2">
                                                <?= strtoupper(substr($row['nama_siswa'], 0, 1)); ?>
                                            </div>
                                            <span title="<?= $row['nama_siswa']; ?>">
                                                <?= strlen($row['nama_siswa']) > 15 ? substr($row['nama_siswa'], 0, 15) . '...' : $row['nama_siswa']; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="fw-medium">
                                        Rp <?= number_format($row['jumlah'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp <?= number_format($row['gaji'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?= ucfirst($row['metode_pembayaran']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'success'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Selesai
                                            </span>
                                        <?php elseif ($row['status'] == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Gagal
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3"></i>
                                        <p class="mb-0">Belum ada transaksi</p>
                                        <small>Transaksi akan muncul setelah siswa membayar kursus</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- INFO -->
            <?php if (!empty($transaksi)): ?>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Menampilkan <?= count($transaksi); ?> transaksi terbaru dari guru <?= !empty($guru['nama']) ? $guru['nama'] : 'ID ' . $guru_id; ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- INFO TAMBAHAN -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>Informasi Sistem Komisi
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-percentage text-success me-2"></i>
                                    <strong>Rasio Komisi:</strong> 80% dari total biaya kursus
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-calendar-alt text-info me-2"></i>
                                    <strong>Pencairan:</strong> Setiap akhir bulan ke rekening bank
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-double text-success me-2"></i>
                                    <strong>Status Success:</strong> Pembayaran sudah diterima sistem
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    <strong>Status Pending:</strong> Menunggu verifikasi pembayaran
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-times text-danger me-2"></i>
                                    <strong>Status Failed:</strong> Pembayaran gagal/dibatalkan
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-file-invoice text-primary me-2"></i>
                                    <strong>Detail:</strong> Klik ID transaksi untuk melihat detail lengkap
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contoh Perhitungan -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="fw-bold">Contoh Perhitungan:</h6>
                        <small class="text-muted">
                            Jika biaya kursus Rp 500.000 → Komisi guru 80% = Rp 400.000<br>
                            Sistem akan memotong 20% untuk biaya operasional platform.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }
</style>