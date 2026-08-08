<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Guru Freelance | WorkyWay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            font-family: 'Segoe UI', sans-serif;
        }

        .register-card {
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,.1);
            border: none;
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            background: #4f46e5;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
        }

        .section-title {
            font-weight: 600;
            color: #4f46e5;
            margin-top: 30px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #4f46e5;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 12px;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 .2rem rgba(79,70,229,.25);
        }

        .footer-text {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-top: 20px;
        }

        .footer-text a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="card register-card p-4 p-md-5">

                <!-- ICON -->
                <div class="icon-circle">
                    <i class="fas fa-user"></i>
                </div>

                <!-- TITLE -->
                <h4 class="text-center fw-bold mb-1">Daftar Guru Freelance</h4>
                <p class="text-center text-muted mb-4">
                    Lengkapi data untuk bergabung sebagai pengajar
                </p>

                <!-- FORM -->
                <form method="post"
                action="<?= base_url('register/proses_guru'); ?>"
                enctype="multipart/form-data">

                    <!-- DATA PRIBADI -->
                    <div class="section-title">
                        <i class="fas fa-id-card"></i> Data Pribadi
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama" class="form-control"
                               placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control"
                               placeholder="email@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="telepon" class="form-control"
                               placeholder="08xxxxxxxxxx"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password"
                                   class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password *</label>
                            <input type="password" name="confirm_password"
                                   class="form-control" required>
                        </div>
                    </div>

                    <!-- DATA PROFESIONAL -->
                    <div class="section-title">
                        <i class="fas fa-briefcase"></i> Data Profesional
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keahlian *</label>
                        <input type="text" name="keahlian" class="form-control"
                               placeholder="Contoh: Web Development, UI/UX, Data Science"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pengalaman Mengajar *</label>
                        <textarea name="pengalaman" class="form-control"
                                  rows="3"
                                  placeholder="Contoh: 5 tahun mengajar di SMA / industri"
                                  required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Upload Portfolio (Opsional)</label>
                        <input type="file" name="portofolio" class="form-control"
                               accept=".pdf,.doc,.docx">
                        <small class="text-muted">
                            Format: PDF / DOC / DOCX (Max 2MB)
                        </small>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-primary w-100">
                        Daftar Sekarang
                    </button>
                </form>

                <!-- FOOTER -->
                <div class="footer-text">
                    Sudah punya akun?
                    <a href="<?= base_url('login') ?>">Masuk di sini</a>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
