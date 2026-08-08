<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil LPK</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#f6f8fc;
    font-family:'Segoe UI', sans-serif;
}
.main {
    margin-left:280px;
    padding:30px;
}
.card {
    border:none;
    border-radius:16px;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<?php $this->load->view('lpk/sidebar', ['menu' => $menu]); ?>

<!-- MAIN -->
<div class="main">

    <!-- HEADER -->
    <div class="d-flex align-items-center mb-4">
        <a href="<?= base_url('lpk/profil_lpk/edit'); ?>" class="me-3 text-decoration-none">←</a>
        <div>
            <h3 class="fw-bold mb-0">Edit Profil</h3>
            <small class="text-muted">Perbarui informasi profil LPK</small>
        </div>
    </div>

    <!-- CARD FORM -->
    <div class="card">
        <div class="card-body">

            <form action="<?= base_url('lpk/profil_lpk/edit'); ?>" method="post" enctype="multipart/form-data">

                <input type="hidden" name="lpk_id" value="<?= $profil['lpk_id']; ?>">

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lembaga</label>
                    <input type="text" class="form-control"
                           name="nama_lembaga"
                           value="<?= $profil['nama_lembaga']; ?>">
                </div>

                <!--FOTO PROFIL-->
                <div class="mb-4">
                <label class="form-label fw-semibold">Foto Profil LPK</label>

                <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($profil['foto_profil'])) : ?>
                        <img src="<?= base_url('uploads/profilLPK/'.$profil['foto_profil'].'?v='.time()); ?>"
                        style="width:90px;height:90px;object-fit:cover;border-radius:12px;">

                    <?php else : ?>
                        <div style="width:90px;height:90px;background:#e5e7eb;border-radius:12px"></div>
                    <?php endif; ?>

                    <input type="file" name="foto_profil" class="form-control">
                </div>

                <small class="text-muted">
                    Format JPG / PNG, maksimal 2MB
                </small>
            </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control bg-light"
                           value="<?= $profil['email']; ?>" readonly>
                </div>

                <!-- ROW -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" class="form-control"
                               name="telepon"
                               value="<?= $profil['telepon']; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tahun Berdiri</label>
                        <input type="number" class="form-control"
                               name="tahun_berdiri"
                               value="<?= $profil['tahun']; ?>">
                    </div>

                </div>

                <!-- ROW -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor Izin</label>
                        <input type="text" class="form-control"
                               name="nomor_izin"
                               value="<?= $profil['izin']; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Akreditasi</label>
                        <input type="text" class="form-control"
                               name="akreditasi"
                               value="<?= $profil['akreditasi']; ?>">
                    </div>

                </div>

                <!-- ALAMAT -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea class="form-control"
                              name="alamat"><?= $profil['alamat']; ?></textarea>
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea class="form-control" rows="4"
                              name="deskripsi"><?= $profil['deskripsi']; ?></textarea>
                </div>

                <!-- ACTION -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('lpk/profil_lpk/edit'); ?>" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
