<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa - WorkyWay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: 12px;
            border: none;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            padding: 10px;
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        .login-link {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
        }
        
        .login-link a {
            color: #0d6efd;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .back-link {
            cursor: pointer;
            color: #6c757d;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .back-link:hover {
            color: #0d6efd;
        }
        
        .form-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Register Form Siswa -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow p-4">
                    <div class="text-center mb-3">
                        <img src="<?= base_url('assets/image/siswa.png'); ?>"
                            alt="WorkyWay"
                            style="max-width:120px;"
                            class="img-fluid">
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="text-center mb-0 w-100">Daftar Siswa</h4>
                    </div>
                    
                    <p class="text-center text-muted mb-4">Bergabung dengan platform kursus kami</p>

                    <?php if (validation_errors()): ?>
                        <div class="alert alert-danger">
                            <?= validation_errors(); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo base_url('register/proses_siswa'); ?>" id="registerForm">
                        <!-- Data Pribadi -->
                        <div class="mb-3">
                            <label class="form-label form-title">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" 
                                   value="<?php echo isset($nama) ? $nama : ''; ?>" 
                                   placeholder="Masukkan nama lengkap" required>
                            <span class="error-message"><?php echo isset($nama_err) ? $nama_err : ''; ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label form-title">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo isset($email) ? $email : ''; ?>" 
                                   placeholder="email@contoh.com" required>
                            <span class="error-message"><?php echo isset($email_err) ? $email_err : ''; ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label form-title">Password</label>
                            <input type="password" name="password" id="password" class="form-control" 
                                   placeholder="Masukkan password" required>
                            <span class="error-message"><?php echo isset($password_err) ? $password_err : ''; ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label form-title">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" 
                                   placeholder="Ketik ulang password" required>
                            <span class="error-message"><?php echo isset($confirm_password_err) ? $confirm_password_err : ''; ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-title">Nomor Telepon</label>
                            <input type="text"
                                name="telepon"
                                class="form-control"
                                value="<?= set_value('telepon'); ?>"
                                placeholder="08xxxxxxxxxx"
                                required
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>

                       <div class="mb-3">
                            <label class="form-label form-title">Jurusan SMK</label>
                            <select name="jurusan_id" class="form-select" required>
                                <option value="">Pilih Jurusan</option>
                                <?php foreach ($jurusan as $j): ?>
                                    <option value="<?= $j->jurusan_id; ?>">
                                        <?= $j->nama_jurusan; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Daftar Sekarang</button>
                    </form>

                    <div class="login-link">
                        <span>Sudah punya akun? <a href="<?php echo base_url('login'); ?>"><strong>Masuk di sini</strong></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <script>
        // Validasi form sebelum submit
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            let isValid = true;          
            
            // Validasi konfirmasi password
            if (password !== confirmPassword) {
                isValid = false;
                document.querySelector('input[name="confirm_password"]').nextElementSibling.textContent = 'Password tidak cocok';
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
        
        // Tampilkan SweetAlert jika ada flash data
        <?php if ($this->session->flashdata('pesan_sukses')): ?>
            swal("Sukses!", "<?php echo $this->session->flashdata('pesan_sukses'); ?>", "success");
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('pesan_gagal')): ?>
            swal("Gagal!", "<?php echo $this->session->flashdata('pesan_gagal'); ?>", "error");
        <?php endif; ?>
    </script>
</body>
</html>