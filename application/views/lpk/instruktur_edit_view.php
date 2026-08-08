<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Instruktur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f8fc;
            font-family: 'Segoe UI', sans-serif;
        }

        .main {
            margin-left: 280px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
        }

        .foto-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3b82f6;
        }
    </style>
</head>
<body>

<?php $this->load->view('lpk/sidebar', ['menu' => $menu]); ?>

<div class="main">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Edit Instruktur</h3>
        <p class="text-muted mb-0">Perbarui data instruktur</p>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">

                    <!-- FOTO -->
                    <div class="col-md-4 text-center mb-4">
                        <?php if (!empty($instruktur['foto'])): ?>
                            <img src="<?= base_url('uploads/instruktur/'.$instruktur['foto']); ?>" class="foto-preview mb-3">
                        <?php else: ?>
                            <div class="foto-preview mb-3 d-flex align-items-center justify-content-center bg-light">
                                <span class="fs-1 text-muted">
                                    <?= substr($instruktur['nama'], 0, 1); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <input type="file" name="foto" class="form-control mt-2">
                        <small class="text-muted">Kosongkan jika tidak mengganti foto</small>
                    </div>

                    <!-- FORM -->
                    <div class="col-md-8">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Instruktur</label>
                                <input type="text" name="nama" class="form-control" required
                                       value="<?= htmlspecialchars($instruktur['nama']); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Keahlian</label>
                                <input type="text" name="keahlian" class="form-control" required
                                       value="<?= htmlspecialchars($instruktur['keahlian']); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="<?= htmlspecialchars($instruktur['email']); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="telp" class="form-control"
                                       value="<?= htmlspecialchars($instruktur['telp']); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="aktif" <?= $instruktur['status'] == 'aktif' ? 'selected' : ''; ?>>
                                        Aktif
                                    </option>
                                    <option value="nonaktif" <?= $instruktur['status'] == 'nonaktif' ? 'selected' : ''; ?>>
                                        Nonaktif
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ACTION -->
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="<?= base_url('lpk/instruktur'); ?>" class="btn btn-outline-secondary">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
