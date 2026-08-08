<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Lembaga</title>

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

.logo-box {
    width:300px;
    height:110px;
    background:#e0ecff;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:36px;
    font-weight:700;
    color:#2563eb;
}

.info-icon {
    width:36px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<?php $this->load->view('lpk/sidebar', ['menu' => $menu]); ?>

<!-- MAIN -->
<div class="main">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Profil Lembaga</h3>
        <a href="<?= base_url('lpk/profil_lpk/edit'); ?>" class="btn btn-outline-primary">
        Edit Profil</a>
    </div>

    <!-- CARD PROFIL -->
    <div class="card mb-4">
        <div class="card-body d-flex gap-4">
            <div class="logo-box p-0 overflow-hidden">
            <?php if (!empty($profil['foto'])): ?>
                <img src="<?= base_url('uploads/profilLPK/'.$profil['foto'].'?v='.time()); ?>"
                    style="width:100%;height:100%;object-fit:cover;">
            <?php else: ?>
                LPK
            <?php endif; ?>
        </div>


            <div>
               <h4 class="fw-bold mb-1"><?= $profil['nama_lembaga']; ?></h4>
                <p class="text-muted mb-3"><?= $profil['deskripsi']; ?></p>

                <div class="d-flex align-items-center mb-2">
                    <img src="<?= base_url('assets/image/icon/email.png'); ?>" 
                        alt="Email" class="icon-img me-2">
                    <?= $profil['email']; ?>
                </div>

                <div class="d-flex align-items-center mb-2">
                    <img src="<?= base_url('assets/image/icon/telepon.png'); ?>" 
                        alt="Telepon" class="icon-img me-2">
                    <?= $profil['telepon']; ?>
                </div>

                <div class="d-flex align-items-center">
                    <img src="<?= base_url('assets/image/icon/location.png'); ?>" 
                        alt="Alamat" class="icon-img me-2">
                    <?= $profil['alamat']; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- INFORMASI TAMBAHAN -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Informasi Tambahan</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <small class="text-muted">Tahun Berdiri</small>
                    <div class="fw-semibold"><?= $profil['tahun']; ?></div>
                </div>

                <div class="col-md-6 mb-3">
                    <small class="text-muted">Nomor Izin</small>
                    <div class="fw-semibold"><?= $profil['izin']; ?></div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Akreditasi</small>
                    <div class="fw-semibold"><?= $profil['akreditasi']; ?></div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Jumlah Instruktur</small>
                    <div class="fw-semibold"><?= $profil['instruktur']; ?> Instruktur</div>
                </div>
            </div>
        </div>
    </div>

    <!-- MEDIA SOSIAL -->
    <div class="card">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Media Sosial</h5>

            <div class="d-flex gap-3">
                <a href="#" class="btn btn-primary">Facebook</a>
                <a href="#" class="btn btn-danger">Instagram</a>
                <a href="#" class="btn btn-info text-white">Twitter</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>
