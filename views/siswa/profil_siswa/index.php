<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa - WorkyWay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Navbar */
        .navbar {
            background: white;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-bottom: 1px solid #eaeaea;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 50px;
        }

        .navbar-brand h2 {
            color: #4f46e5;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .navbar-menu ul {
            display: flex;
            list-style: none;
            gap: 25px;
            margin: 0;
            padding: 0;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-menu a:hover {
            color: #4f46e5;
            background: #f5f3ff;
            transform: translateY(-2px);
        }

        .navbar-menu .active a {
            color: #4f46e5;
            background: #f5f3ff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }

        /* Header Profil */
        .header {
            background: white;
            padding: 40px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            border: none;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e0e7ff;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .profile-avatar i {
            font-size: 56px;
            color: #667eea;
        }

        .profile-info h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #2d3748;
        }

        .profile-info .email {
            font-size: 18px;
            color: #718096;
            margin-bottom: 25px;
            font-weight: 500;
        }

        /* Main Content Grid */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        /* Kolom Kiri */
        .left-column {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            height: fit-content;
            border: none;
        }

        /* Kolom Kanan */
        .right-column {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            border: none;
        }

        .card-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #2d3748;
            padding-bottom: 15px;
            border-bottom: 3px solid #e0e7ff;
            position: relative;
        }

        .card-title:after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 3px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            gap: 25px;
        }

        .info-item {
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 14px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .info-value {
            font-size: 17px;
            color: #2d3748;
            font-weight: 500;
            padding: 12px 0;
            border-bottom: 2px solid #f7fafc;
        }

        .info-input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f7fafc;
        }

        .info-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Password Form */
        .password-form {
            display: grid;
            gap: 25px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Kursus Item */
        .kursus-item {
            background: white;
            border: 2px solid #f7fafc;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s;
            margin-bottom: 20px;
            position: relative;
        }

        .kursus-item:hover {
            border-color: #e0e7ff;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .kursus-title {
            font-size: 19px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .kursus-details {
            font-size: 15px;
            color: #718096;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .kursus-stats {
            display: flex;
            gap: 15px;
            margin: 15px 0;
            flex-wrap: wrap;
        }

        .stat-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-nilai {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-progress {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stat-tugas {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        .stat-selesai {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-lulus {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        /* Progress Bar */
        .progress-container {
            margin: 15px 0;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e2e8f0;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 5px;
            transition: width 1s ease;
        }

        .progress-text {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #718096;
        }

        /* Action Buttons */
        .kursus-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .download-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .download-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.4);
        }

        .download-btn:disabled {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            cursor: not-allowed;
            opacity: 0.7;
        }

        .info-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        /* Warning Box */
        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .warning-box i {
            color: #f59e0b;
            font-size: 18px;
        }

        .warning-text {
            color: #92400e;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 60px;
            color: #e2e8f0;
            margin-bottom: 20px;
            opacity: 0.6;
        }

        .empty-state p {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state small {
            font-size: 14px;
            color: #cbd5e0;
        }

        /* Flash Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px 20px;
            }
            
            .navbar-left {
                flex-direction: column;
                gap: 15px;
                width: 100%;
                text-align: center;
            }
            
            .navbar-menu ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 25px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .card {
                padding: 25px;
            }
            
            .kursus-stats {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .kursus-actions {
                flex-direction: column;
            }
            
            .download-btn, .info-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <div class="navbar-brand">
                <h2>WorkyWay</h2>
            </div>
        </div>
        <div class="navbar-menu">
            <ul>
                <li><a href="<?php echo site_url('siswa/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo site_url('siswa/kursus'); ?>">Kursus</a></li>
                <li class="active"><a href="<?php echo site_url('siswa/profil_siswa'); ?>">Profil</a></li>
                <li><a href="<?php echo site_url('login/logout'); ?>">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('success_password')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success_password'); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error_password')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error_password'); ?>
            </div>
        <?php endif; ?>

        <!-- Header Profil -->
        <div class="header">
            <div class="profile-header">
                <?php if($user->foto_profil && file_exists(FCPATH . 'uploads/profiles/' . $user->foto_profil)): ?>
                    <img src="<?php echo base_url('uploads/profiles/' . $user->foto_profil); ?>" 
                         alt="Foto Profil" 
                         class="profile-avatar">
                <?php else: ?>
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
                
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($user->nama); ?></h1>
                    <p class="email"><?php echo htmlspecialchars($user->email); ?></p>
                    <p style="color: #718096; font-size: 16px; font-weight: 500;">
                        <i class="fas fa-user-graduate"></i> Siswa - Member sejak <?php echo date('d F Y', strtotime($user->tanggal_daftar)); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Kolom Kiri: Informasi Pribadi -->
            <div class="left-column">
                <div class="card-title">Informasi Pribadi</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value"><?php echo htmlspecialchars($user->nama); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($user->email); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-value">
                            <?php echo $user->telepon ? htmlspecialchars($user->telepon) : 'Belum diisi'; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Bergabung</div>
                        <div class="info-value"><?php echo date('d F Y', strtotime($user->tanggal_daftar)); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Total Kursus</div>
                        <div class="info-value" style="color: #4f46e5; font-weight: 700; font-size: 24px;">
                            <?php echo !empty($riwayat_kursus) ? count($riwayat_kursus) : '0'; ?> 📚
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="right-column">
                <!-- Form Edit Profil -->
                <div class="card">
                    <div class="card-title">Edit Profil</div>
                    <form action="<?php echo site_url('siswa/profil_siswa/update_profil'); ?>" method="post" enctype="multipart/form-data" class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Nama Lengkap</div>
                            <input type="text" name="nama" class="info-input" 
                                   value="<?php echo htmlspecialchars($user->nama); ?>" required>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <input type="email" class="info-input" 
                                   value="<?php echo htmlspecialchars($user->email); ?>" disabled>
                            <small style="color: #718096; font-size: 14px; display: block; margin-top: 8px; font-weight: 500;">Email tidak dapat diubah</small>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Nomor Telepon</div>
                            <input type="text" name="telepon" class="info-input" 
                                   value="<?php echo htmlspecialchars($user->telepon); ?>">
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Foto Profil</div>
                            <input type="file" name="foto_profil" class="info-input" accept="image/*">
                            <small style="color: #718096; font-size: 14px; display: block; margin-top: 8px; font-weight: 500;">Maksimal 2MB (JPG, PNG, GIF)</small>
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Form Ubah Password -->
                <div class="card">
                    <div class="card-title">Ubah Password</div>
                    <form action="<?php echo site_url('siswa/profil_siswa/update_password'); ?>" method="post" class="password-form">
                        <div class="info-item">
                            <div class="info-label">Password Lama</div>
                            <input type="password" name="password_lama" class="info-input" 
                                   placeholder="Masukkan password lama" required>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Password Baru</div>
                            <input type="password" name="password_baru" class="info-input" 
                                   placeholder="Masukkan password baru" minlength="6" required>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Konfirmasi Password Baru</div>
                            <input type="password" name="konfirmasi_password" class="info-input" 
                                   placeholder="Konfirmasi password baru" required>
                        </div>
                        
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>

                <!-- Bagian Sertifikat & Riwayat Kursus -->
                <div class="card">
                    <div class="card-title">Riwayat Kursus & Sertifikat</div>
                    <div class="item-list">
                        <?php if (!empty($processed_kursus)): ?>
                            <?php foreach ($processed_kursus as $kursus): 
                                // Tentukan status kursus
                                $tanggal_selesai = strtotime($kursus['tanggal_selesai']);
                                $sekarang = time();
                                $kursus_selesai = $sekarang >= $tanggal_selesai;
                                
                                // Tampilkan semua kursus (baik yang sudah selesai maupun belum)
                            ?>
                            <div class="kursus-item">
                                <div class="kursus-title">
                                    <i class="fas fa-book"></i>
                                    <?php echo htmlspecialchars($kursus['judul_kursus']); ?>
                                </div>
                                
                                <div class="kursus-details">
                                    <span><i class="far fa-calendar-alt"></i> Mulai: <?php echo date('d M Y', strtotime($kursus['tanggal_daftar'])); ?></span>
                                    <span><i class="fas fa-clock"></i> Durasi: <?php echo htmlspecialchars($kursus['durasi']); ?></span>
                                    <span><i class="fas fa-calendar-check"></i> Selesai: <?php echo date('d M Y', $tanggal_selesai); ?></span>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo $kursus['progress']; ?>%; 
                                            background: <?php 
                                                if ($kursus['progress'] >= 80) echo '#10b981';
                                                elseif ($kursus['progress'] >= 60) echo '#3b82f6';
                                                elseif ($kursus['progress'] >= 30) echo '#f59e0b';
                                                else echo '#ef4444';
                                            ?>;">
                                        </div>
                                    </div>
                                    <div class="progress-text">
                                        <span>Progress: <?php echo $kursus['progress']; ?>%</span>
                                        <span><?php echo $kursus['progress']; ?>% selesai</span>
                                    </div>
                                </div>
                                
                                <!-- Stats -->
                                <div class="kursus-stats">
                                    <span class="stat-badge stat-nilai">
                                        <i class="fas fa-star"></i> Nilai: <?php echo $kursus['nilai_akhir']; ?>
                                    </span>
                                    
                                    <span class="stat-badge stat-tugas">
                                        <i class="fas fa-tasks"></i> 
                                        Tugas: <?php echo $kursus['info_tugas']['tugas_dinilai']; ?>/<?php echo $kursus['info_tugas']['total_tugas']; ?>
                                    </span>
                                    
                                    <?php if($kursus_selesai): ?>
                                        <span class="stat-badge stat-selesai">
                                            <i class="fas fa-check-circle"></i> Kursus Selesai
                                        </span>
                                    <?php else: ?>
                                        <span class="stat-badge" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white;">
                                            <i class="fas fa-spinner"></i> Masih Berjalan
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if($kursus['bisa_download']): ?>
                                        <span class="stat-badge" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                                            <i class="fas fa-award"></i> Sertifikat Tersedia
                                        </span>
                                    <?php elseif($kursus_selesai && $kursus['progress'] < 80): ?>
                                        <span class="stat-badge stat-lulus">
                                            <i class="fas fa-times-circle"></i> Tidak Lulus
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Warning jika tidak memenuhi syarat -->
                                <?php if($kursus_selesai && !$kursus['bisa_download']): ?>
                                    <div class="warning-box">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <div class="warning-text">
                                            <?php if($kursus['progress'] < 80): ?>
                                                <strong>Belum memenuhi syarat sertifikat!</strong> Progress harus minimal 80% untuk mendapatkan sertifikat. Progress saat ini: <?php echo $kursus['progress']; ?>%.
                                            <?php else: ?>
                                                <strong>Kursus belum selesai!</strong> Tunggu sampai tanggal <?php echo date('d M Y', $tanggal_selesai); ?> untuk mendapatkan sertifikat.
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Action Buttons -->
                                <div class="kursus-actions">
                                    <?php if($kursus['bisa_download']): ?>
                                        <a href="<?php echo site_url('siswa/profil_siswa/download_sertifikat/' . $kursus['enrollment_id']); ?>" 
                                           class="download-btn">
                                            <i class="fas fa-download"></i> Download Sertifikat
                                        </a>
                                    <?php else: ?>
                                        <button class="download-btn" disabled>
                                            <i class="fas fa-lock"></i> Sertifikat Belum Tersedia
                                        </button>
                                    <?php endif; ?>
                                    
                                    <span class="info-btn">
                                        <i class="fas fa-info-circle"></i> Detail: <?php echo $kursus['kategori']['nama']; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-book"></i>
                                <p>Belum ada riwayat kursus</p>
                                <small>Ikuti kursus untuk melihat riwayat</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>