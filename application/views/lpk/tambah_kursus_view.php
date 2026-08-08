<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Kursus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background:#f6f8fc;
    font-family:'Segoe UI', sans-serif;
}
.container {
    max-width: 800px;
}
.card {
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
}
</style>
</head>
<body>

<div class="container mt-5">
    <div class="card p-4">
        <h3 class="mb-4">Tambah Kursus Baru</h3>

        <a href="<?= base_url('lpk/kursus'); ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

        <form action="" method="post">

            <!-- Kategori -->
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($kategori as $k): ?>
                        <option value="<?= $k['kategori_id']; ?>"><?= $k['nama_kategori']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Guru</label>
                <select name="guru_id" class="form-select" required>
                <option value="">-- Pilih Guru --</option>
                <?php foreach($guru as $g): ?>
                    <option value="<?= $g['guru_id']; ?>"><?= $g['nama']; ?></option>
                <?php endforeach; ?>
            </select>
            </div>

            <!-- Judul Kursus -->
            <div class="mb-3">
                <label class="form-label">Judul Kursus</label>
                <input type="text" name="judul_kursus" class="form-control" required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="deskripsi" class="form-control" rows="2" required></textarea>
            </div>

            <!-- Detail -->
            <div class="mb-3">
                <label class="form-label">Detail Kursus</label>
                <textarea name="detail" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Biaya -->
            <div class="mb-3">
                <label class="form-label">Biaya (Rp)</label>
                <input type="number" name="biaya" class="form-control" required>
            </div>

            <!-- Durasi -->
            <div class="mb-3">
                <label class="form-label">Durasi</label>
                <input type="text" name="durasi" class="form-control" placeholder="Contoh: 3 bulan" required>
            </div>

            <!-- Jadwal Mulai -->
            <div class="mb-3">
                <label class="form-label">Jadwal Mulai</label>
                <input type="date" name="jadwal_mulai" class="form-control" required>
            </div>

            <!-- Jadwal Selesai -->
            <div class="mb-3">
                <label class="form-label">Jadwal Selesai</label>
                <input type="date" name="jadwal_selesai" class="form-control" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status Kursus</label>
                <select name="status_kursus" class="form-select" required>
                    <option value="active">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tambah Kursus</button>

        </form>
    </div>
</div>

</body>
</html>
