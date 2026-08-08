<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Kursus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <a href="<?= base_url('lpk/kursus'); ?>" class="btn btn-secondary mb-3">
        ← Kembali
    </a>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Kursus</h5>
        </div>

        <div class="card-body">
            <form method="post">

                <!-- KATEGORI -->
                <div class="mb-3">
                    <label class="form-label">Kategori Kursus</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['kategori_id']; ?>"
                                <?= $k['kategori_id'] == $kursus['kategori_id'] ? 'selected' : ''; ?>>
                                <?= $k['nama_kategori']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- GURU -->
                <div class="mb-3">
                    <label class="form-label">Guru Pengajar</label>
                    <select name="guru_id" class="form-select" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($guru as $g): ?>
                            <option value="<?= $g['guru_id']; ?>"
                                <?= $g['guru_id'] == $kursus['guru_id'] ? 'selected' : ''; ?>>
                                <?= $g['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- JUDUL -->
                <div class="mb-3">
                    <label class="form-label">Judul Kursus</label>
                    <input type="text" name="judul_kursus" class="form-control"
                           value="<?= $kursus['judul_kursus']; ?>" required>
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3"><?= $kursus['deskripsi']; ?></textarea>
                </div>

                <!-- DETAIL -->
                <div class="mb-3">
                    <label class="form-label">Detail Kursus</label>
                    <textarea name="detail" class="form-control" rows="4"><?= $kursus['detail']; ?></textarea>
                </div>

                <div class="row">
                    <!-- BIAYA -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Biaya (Rp)</label>
                        <input type="number" step="0.01" name="biaya" class="form-control"
                               value="<?= $kursus['biaya']; ?>">
                    </div>

                    <!-- DURASI -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Durasi</label>
                        <input type="text" name="durasi" class="form-control"
                               value="<?= $kursus['durasi']; ?>">
                    </div>
                </div>

                <div class="row">
                    <!-- JADWAL MULAI -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jadwal Mulai</label>
                        <input type="date" name="jadwal_mulai" class="form-control"
                            value="<?= $kursus['jadwal_mulai'] ?? ''; ?>">
                    </div>

                    <!-- JADWAL SELESAI -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jadwal Selesai</label>
                        <input type="date" name="jadwal_selesai" class="form-control"
                            value="<?= $kursus['jadwal_selesai'] ?? ''; ?>">
                    </div>
                </div>

                <!-- STATUS -->
                <div class="mb-3">
                    <label class="form-label">Status Kursus</label>
                    <select name="status_kursus" class="form-select">
                        <option value="pending" <?= $kursus['status_kursus']=='pending'?'selected':'' ?>>Pending</option>
                        <option value="active" <?= $kursus['status_kursus']=='active'?'selected':'' ?>>Aktif</option>
                        <option value="inactive" <?= $kursus['status_kursus']=='inactive'?'selected':'' ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        Update Kursus
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>
