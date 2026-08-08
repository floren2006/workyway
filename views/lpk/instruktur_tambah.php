<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Instruktur</title>
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
        .form-label {
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>

<?php $this->load->view('lpk/sidebar', ['menu' => $menu]); ?>

<div class="main">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Tambah Instruktur</h3>
        <a href="<?= base_url('lpk/instruktur'); ?>" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

    <!-- FORM TAMBAH -->
    <div class="card">
        <div class="card-body p-4">
            <form method="post" action="<?= base_url('lpk/instruktur/tambah'); ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Nama Instruktur</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bidang Keahlian</label>
                            <input type="text" name="keahlian" class="form-control" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="telp" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Foto Instruktur</label>
                            <div class="border rounded p-3 text-center">
                                <div id="preview-container" class="mb-3">
                                    <img id="preview" src="<?= base_url('assets/image/icon/user-empty.png'); ?>" 
                                         width="120" height="120" class="rounded-circle object-fit-cover">
                                </div>
                                <input type="file" name="foto" class="form-control" 
                                       accept="image/*" onchange="previewImage(this)">
                                <div class="form-text">
                                    Upload foto (max 2MB, JPG/PNG)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('lpk/instruktur'); ?>" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function previewImage(input) {
    var preview = document.getElementById('preview');
    var previewContainer = document.getElementById('preview-container');
    
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>