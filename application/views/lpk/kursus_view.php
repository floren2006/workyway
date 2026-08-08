<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Kursus</title>

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
.badge-aktif {
    background:#dcfce7;
    color:#166534;
}
.badge-nonaktif {
    background:#fee2e2;
    color:#991b1b;
}
.icon-btn {
    width:34px;
    height:34px;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
}
</style>
</head>

<body>

<?php $this->load->view('lpk/sidebar', ['menu' => $menu]); ?>

<div class="main">

<!--Notif-->
    <!--Notif ubah-->
    <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Manajemen Kursus</h3>
    <a href="<?= base_url('lpk/kursus/tambah'); ?>" class="btn btn-primary">
        + Tambah Kursus
    </a>
</div>

<!-- TABEL -->
<div class="card mb-4">
    <div class="card-body">

        <table class="table align-middle">
            <thead class="text-muted small">
                <tr>
                    <th>Judul Kursus</th>
                    <th>Durasi</th>
                    <th>Biaya</th>
                    <th>Peserta</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($kursus as $k): ?>
                <tr>
                    <td class="fw-semibold"><?= $k['judul_kursus']; ?></td>
                    <td><?= $k['durasi']; ?></td>
                    <td>Rp <?= number_format($k['biaya'],0,',','.'); ?></td>
                    <td><?= $k['peserta']; ?></td>
                    <td>
                        <?php if ($k['status_kursus'] === 'active'): ?>
                            <span class="badge badge-aktif px-3 py-2">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-nonaktif px-3 py-2">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="aksi-col">
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="<?= base_url('lpk/kursus/edit/'.$k['kursus_id']); ?>"
                            class="icon-btn border">
                                <img src="<?= base_url('assets/image/icon/edit.png'); ?>" width="18">
                            </a>

                            <a href="<?= base_url('lpk/kursus/hapus/'.$k['kursus_id']); ?>"
                            class="icon-btn border">
                                <img src="<?= base_url('assets/image/icon/hapus.png'); ?>" width="18">
                            </a>
                            
                            <a href="<?= base_url('lpk/materi/'.$k['kursus_id']); ?>"
                            class="icon-btn border">
                                <img src="<?= base_url('assets/image/icon/materi.png'); ?>" width="18">
                            </a>
                        </div>
                    </td>


                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

    </div>
</div>

<!-- UPLOAD MATERI -->
<div class="card mb-4">
    <div class="card-body text-center py-5">

        <h5 class="fw-bold mb-3">Upload Materi Kursus</h5>

        <!-- KLIK AREA -->
        <div class="border rounded-4 p-5 bg-light upload-card"
             data-bs-toggle="modal"
             data-bs-target="#modalUploadMateri"
             style="cursor:pointer;">

            <div class="mb-3">
                <img src="<?= base_url('assets/image/icon/upload.png'); ?>"
                     width="48">
            </div>

            <p class="text-muted mb-0">
                Klik untuk upload video, dokumen, atau kuis
            </p>
        </div>

    </div>
</div>


<div class="modal fade" id="modalUploadMateri" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-body p-4 text-center">

                <h5 class="fw-bold mb-3">Upload Materi Kursus</h5>

                <form action="<?= base_url('lpk/materi/upload'); ?>"
                      method="post"
                      enctype="multipart/form-data">

                    <div class="mb-3">
                        <img src="<?= base_url('assets/image/icon/upload.png'); ?>" width="40">
                    </div>

                    <p class="text-muted mb-3">
                        Pilih kursus dan file materi
                    </p>

                    <!-- PILIH KURSUS -->
                    <select name="kursus_id" class="form-select mb-3" required>
                        <option value="">-- Pilih Kursus --</option>
                        <?php foreach ($kursus as $k): ?>
                            <option value="<?= $k['kursus_id']; ?>">
                                <?= $k['judul_kursus']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- FILE -->
                    <input type="file"
                           name="file_materi"
                           class="form-control mb-4"
                           required>

                    <!-- BUTTON -->
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                                class="btn btn-primary">
                            Upload
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
