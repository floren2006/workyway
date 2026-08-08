<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Lembaga Pelatihan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(180deg,#eef4ff,#f8f9fa);
            font-family: 'Segoe UI', sans-serif;
        }
        .card{
            border-radius: 16px;
            border: none;
        }
        .icon-circle{
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            margin: 0 auto 10px;
        }
        .section-title{
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary{
            background: #4f46e5;
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover{
            background: #4338ca;
        }
        .form-control:focus{
            box-shadow: 0 0 0 .2rem rgba(79,70,229,.25);
            border-color: #4f46e5;
        }
        .required{
            color: #dc3545;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow p-4">

                <!-- ICON -->
                <div class="icon-circle">
                    <i class="fas fa-building"></i>
                </div>

                <!-- TITLE -->
                <h4 class="text-center fw-bold mt-2">Daftar Lembaga Pelatihan</h4>
                <p class="text-center text-muted mb-4">
                    Lengkapi informasi lembaga pelatihan kerja Anda
                </p>

                <form method="post" action="<?= base_url('register/proses_lpk'); ?>">

                    <!-- NAMA LEMBAGA -->
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Lembaga <span class="required">*</span>
                        </label>
                        <input type="text" name="nama_lembaga" class="form-control"
                               placeholder="Contoh: LPK Karya Mandiri" required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Masukkan password" required>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label">
                            Konfirmasi Password <span class="required">*</span>
                        </label>
                        <input type="password" name="confirm_password" class="form-control"
                               placeholder="Ulangi password" required>
                    </div>

                    <!-- INFORMASI KONTAK -->
                    <div class="section-title">
                        <i class="fas fa-envelope"></i> Informasi Kontak
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="email@lembaga.com" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nomor Telepon <span class="required">*</span>
                            </label>
                            <input type="text" name="telepon" class="form-control"
                                   placeholder="08123456789"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                        </div>
                    </div>

                    <!-- ALAMAT -->
                    <div class="mb-4">
                        <label class="form-label">
                            Alamat <span class="required">*</span>
                        </label>
                        <textarea name="alamat" class="form-control" rows="3"
                                  placeholder="Alamat lengkap lembaga" required></textarea>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-primary w-100">
                        Daftar Sekarang
                    </button>

                    <!-- LOGIN LINK -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Sudah punya akun?
                            <a href="<?= base_url('login'); ?>" class="fw-semibold text-decoration-none">
                                Masuk di sini
                            </a>
                        </small>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
