<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - WorkyWay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        /* Navbar Styles */
        .navbar {
            background: white;
            padding: 15px 40px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 60px;
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
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            font-size: 16px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-menu a:hover {
            color: #4f46e5;
            background: #f5f3ff;
        }

        .navbar-menu .active a {
            color: #4f46e5;
            background: #f5f3ff;
            font-weight: 600;
        }

        /* Main Content */
        .dashboard-content {
            padding: 40px;
            min-height: calc(100vh - 120px);
        }

        .welcome-section {
            margin-bottom: 40px;
        }

        .welcome-section h1 {
            color: #1e293b;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
        }

        .welcome-section h1 span {
            color: #4f46e5;
        }

        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 30px;
            border-radius: 16px;
            color: white;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(79, 70, 229, 0.4);
        }

        .stat-card h3 {
            font-size: 16px;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .stat-card h2 {
            font-size: 48px;
            font-weight: 700;
        }

        /* Main Layout */
        .main-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        .left-column {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* KURSUS SAYA */
        .kursus-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .kursus-item {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .kursus-item:hover {
            border-color: #c7d2fe;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        /* Gambar Kursus */
        .kursus-image-container {
            flex-shrink: 0;
            width: 100px;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kursus-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .kursus-item:hover .kursus-image {
            transform: scale(1.05);
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            color: #64748b;
        }

        .image-placeholder svg {
            width: 40px;
            height: 40px;
            opacity: 0.5;
        }

        /* Konten Kursus */
        .kursus-content {
            flex: 1;
            min-width: 0;
        }

        .kursus-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            cursor: pointer;
            transition: color 0.3s ease;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .kursus-title:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .kursus-instructor {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 12px;
        }

        .kursus-meta {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #64748b;
        }

        .kursus-duration, .kursus-status {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .progress-section {
            margin: 16px 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            color: #64748b;
        }

        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            border-radius: 4px;
            transition: width 1s ease;
        }

        .tugas-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .next-lesson {
            font-size: 14px;
            color: #4f46e5;
            font-weight: 500;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* REKOMENDASI KURSUS */
        .rekomendasi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .rekomendasi-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background: white;
        }

        .rekomendasi-card:hover {
            border-color: #c7d2fe;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.15);
            transform: translateY(-4px);
        }

        .rekomendasi-image-container {
            width: 100%;
            height: 160px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .rekomendasi-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .rekomendasi-card:hover .rekomendasi-image {
            transform: scale(1.05);
        }

        .rekomendasi-content {
            padding: 20px;
        }

        .rekomendasi-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 40px;
            line-height: 1.4;
        }

        .rekomendasi-instructor {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .course-meta {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .rating, .students, .duration {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #64748b;
        }

        .rating {
            color: #f59e0b;
            font-weight: 500;
        }

        .price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .price {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .price.gratis {
            color: #10b981;
        }

        .enroll-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: inherit;
            font-size: 14px;
            display: inline-block;
            text-align: center;
            min-width: 80px;
        }

        .enroll-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Right Column - Notifikasi */
        .right-column {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .notifikasi-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .notifikasi-item {
            border-left: 4px solid #4f46e5;
            background: #f8fafc;
            padding: 20px;
            border-radius: 0 12px 12px 0;
            transition: all 0.3s ease;
        }

        .notifikasi-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }

        .notifikasi-item.unread {
            border-left-color: #10b981;
            background: #f0fdf4;
        }

        .notifikasi-text {
            color: #1e293b;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .notifikasi-time {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* See All Button */
        .see-all {
            color: #4f46e5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
        }

        .see-all:hover {
            gap: 8px;
        }

        /* No Content Message */
        .no-content {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .no-content svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .no-content h4 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #475569;
        }

        .no-content p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .explore-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .explore-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .right-column {
                grid-row: 1;
            }
        }

        @media (max-width: 1024px) {
            .navbar {
                padding: 15px 20px;
            }
            
            .navbar-left {
                gap: 30px;
            }
            
            .navbar-menu ul {
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                border-radius: 16px;
            }
            
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
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
                width: 100%;
            }
            
            .dashboard-content {
                padding: 20px;
            }
            
            .stats-section {
                grid-template-columns: 1fr;
            }
            
            .kursus-item {
                padding: 20px;
                flex-direction: column;
            }
            
            .kursus-image-container {
                width: 100%;
                height: 120px;
                margin-bottom: 16px;
            }
            
            .section-card {
                padding: 20px;
            }
            
            .rekomendasi-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .navbar-menu ul {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
            
            .welcome-section h1 {
                font-size: 24px;
            }
            
            .stat-card h2 {
                font-size: 36px;
            }
            
            .kursus-meta {
                flex-wrap: wrap;
            }
            
            .price-section {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
            
            .enroll-btn {
                width: 100%;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        .stagger-delay-1 { animation-delay: 0.1s; }
        .stagger-delay-2 { animation-delay: 0.2s; }
        .stagger-delay-3 { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <div class="navbar-brand">
                    <h2>WorkyWay</h2>
                </div>
            </div>
            <div class="navbar-menu">
                <ul>
                    <li class="active"><a href="<?php echo site_url('siswa/dashboard'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo site_url('siswa/kursus'); ?>">Kursus</a></li>
                    <li><a href="<?php echo site_url('siswa/profil'); ?>">Profil</a></li>
                    <li><a href="<?php echo site_url('login/logout'); ?>">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="dashboard-content">
            <div class="welcome-section">
                <?php if(isset($siswa) && $siswa): ?>
                <h1>Selamat datang kembali, <span><?php echo htmlspecialchars(explode(' ', $siswa->nama)[0]); ?></span>!<br>
                    Mari lanjutkan belajarmu.</h1>
                <?php else: ?>
                <h1>Selamat datang kembali!<br>
                    Mari lanjutkan belajarmu.</h1>
                <?php endif; ?>
            </div>

            <!-- Stats Cards -->
            <div class="stats-section">
                <div class="stat-card fade-in">
                    <h3>Kursus Aktif</h3>
                    <h2><?php echo isset($kursus_aktif) ? $kursus_aktif : '0'; ?></h2>
                </div>
                <div class="stat-card fade-in stagger-delay-1">
                    <h3>Certifikat</h3>
                    <h2><?php echo isset($sertifikat) ? $sertifikat : '0'; ?></h2>
                </div>
                <div class="stat-card fade-in stagger-delay-2">
                    <h3>Progress Rata-rata</h3>
                    <h2><?php echo isset($progress_rata) ? $progress_rata . '%' : '0%'; ?></h2>
                </div>
            </div>

            <div class="main-content">
                <div class="left-column">
                    <!-- Kursus Saya -->
                    <div class="section-card fade-in">
                        <div class="section-title">
                            <span>Kursus Saya</span>
                            <a href="<?php echo site_url('siswa/kursus/kursus_saya'); ?>" class="see-all">Lihat Semua →</a>
                        </div>
                        
                        <?php if(isset($kursus_dashboard) && !empty($kursus_dashboard)): ?>
                            <div class="kursus-list">
                                <?php foreach($kursus_dashboard as $index => $kursus): ?>
                                <?php 
                                    // Path gambar kursus
                                    $gambar_path = !empty($kursus->gambar_kursus) && $kursus->gambar_kursus != 'default-course.jpg' 
                                        ? base_url('uploads/kursus/' . $kursus->gambar_kursus) 
                                        : base_url('assets/images/default-course.jpg');
                                    
                                    $gambar_available = !empty($kursus->gambar_kursus) && $kursus->gambar_kursus != 'default-course.jpg';
                                ?>
                                <div class="kursus-item fade-in stagger-delay-<?php echo ($index % 3) + 1; ?>" 
                                     onclick="window.location.href='<?php echo site_url("siswa/modul/{$kursus->kursus_id}"); ?>'">
                                    
                                    <!-- Gambar Kursus -->
                                    <div class="kursus-image-container">
                                        <?php if($gambar_available): ?>
                                            <img src="<?php echo $gambar_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($kursus->judul_kursus); ?>"
                                                 class="kursus-image"
                                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-placeholder\'><svg width=\'40\' height=\'40\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'></rect><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'></circle><polyline points=\'21 15 16 10 5 21\'></polyline></svg></div>';">
                                        <?php else: ?>
                                            <div class="image-placeholder">
                                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Konten Kursus -->
                                    <div class="kursus-content">
                                        <h5 class="kursus-title">
                                            <?php echo htmlspecialchars($kursus->judul_kursus); ?>
                                        </h5>
                                        <div class="kursus-instructor">oleh: <?php echo htmlspecialchars($kursus->instruktur); ?></div>
                                        
                                        <div class="kursus-meta">
                                            <span class="kursus-duration">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M12 8v4l3 3"/>
                                                    <circle cx="12" cy="12" r="10"/>
                                                </svg>
                                                <?php echo isset($kursus->durasi) ? htmlspecialchars($kursus->durasi) : '3 Bulan'; ?>
                                            </span>
                                            <span class="kursus-status">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                                                </svg>
                                                <?php echo isset($kursus->sisa_hari) && $kursus->sisa_hari > 0 ? "Sisa {$kursus->sisa_hari} hari" : "Hari terakhir"; ?>
                                            </span>
                                        </div>
                                        
                                        <div class="progress-section">
                                            <div class="progress-label">
                                                <span>Progress</span>
                                                <span><?php echo isset($kursus->progress) ? $kursus->progress . '%' : '0%'; ?></span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: <?php echo isset($kursus->progress) ? $kursus->progress : 0; ?>%;"></div>
                                            </div>
                                            <div class="tugas-info">
                                                <?php echo isset($kursus->tugas_selesai) ? $kursus->tugas_selesai : 0; ?> dari <?php echo isset($kursus->total_tugas) ? $kursus->total_tugas : 0; ?> tugas selesai
                                            </div>
                                        </div>
                                        
                                        <?php if(isset($kursus->progress) && $kursus->progress < 100): ?>
                                        <div class="next-lesson">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 18l6-6-6-6"/>
                                            </svg>
                                            Lanjutkan belajar
                                        </div>
                                        <?php elseif(isset($kursus->progress) && $kursus->progress == 100): ?>
                                        <div class="next-lesson" style="color: #10b981;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 6L9 17l-5-5"/>
                                            </svg>
                                            Kursus selesai
                                        </div>
                                        <?php else: ?>
                                        <div class="next-lesson">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                                            </svg>
                                            Mulai belajar
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-content">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <h4>Belum ada kursus aktif</h4>
                                <p>Daftar kursus sekarang untuk mulai belajar!</p>
                                <a href="<?php echo site_url('siswa/kursus'); ?>" class="explore-btn">Jelajahi Kursus</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Rekomendasi Kursus -->
                    <div class="section-card fade-in stagger-delay-1">
                        <div class="section-title">
                            <span>Rekomendasi Kursus</span>
                            <a href="<?php echo site_url('siswa/kursus'); ?>" class="see-all">Lihat Semua →</a>
                        </div>
                        
                        <?php if(isset($rekomendasi_kursus) && !empty($rekomendasi_kursus)): ?>
                            <div class="rekomendasi-grid">
                                <?php foreach($rekomendasi_kursus as $index => $rek): ?>
                                <?php 
                                    // Path gambar kursus
                                    $gambar_path = !empty($rek->gambar_kursus) && $rek->gambar_kursus != 'default-course.jpg' 
                                        ? base_url('uploads/kursus/' . $rek->gambar_kursus) 
                                        : base_url('assets/images/default-course.jpg');
                                    
                                    $gambar_available = !empty($rek->gambar_kursus) && $rek->gambar_kursus != 'default-course.jpg';
                                    
                                    // Format harga
                                    $harga_formatted = ($rek->biaya == 0) ? 'Gratis' : 'Rp ' . number_format($rek->biaya, 0, ',', '.');
                                    
                                    // Default durasi jika tidak ada
                                    $durasi = isset($rek->durasi) ? $rek->durasi : '3 Bulan';
                                ?>
                                <div class="rekomendasi-card fade-in stagger-delay-<?php echo ($index % 3) + 1; ?>"
                                     onclick="window.location.href='<?php echo site_url("siswa/kursus/detail/{$rek->kursus_id}"); ?>'">
                                    
                                    <!-- Gambar Kursus -->
                                    <div class="rekomendasi-image-container">
                                        <?php if($gambar_available): ?>
                                            <img src="<?php echo $gambar_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($rek->judul_kursus); ?>"
                                                 class="rekomendasi-image"
                                                 onerror="this.onerror=null; this.parentElement.style.background='linear-gradient(135deg, #e2e8f0, #cbd5e1)'; this.parentElement.innerHTML='<div style=\'display: flex; align-items: center; justify-content: center; height: 100%; color: #64748b; opacity: 0.5;\'><svg width=\'48\' height=\'48\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'></rect><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'></circle><polyline points=\'21 15 16 10 5 21\'></polyline></svg></div>';">
                                        <?php else: ?>
                                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: linear-gradient(135deg, #e2e8f0, #cbd5e1); color: #64748b; opacity: 0.5;">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Konten Kursus -->
                                    <div class="rekomendasi-content">
                                        <div class="rekomendasi-title"><?php echo htmlspecialchars($rek->judul_kursus); ?></div>
                                        <div class="rekomendasi-instructor">oleh: <?php echo htmlspecialchars($rek->instruktur); ?></div>
                                        
                                        <div class="course-meta">
                                            <span class="rating">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="#fbbf24" stroke="none">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                                <?php echo number_format($rek->rating_rata2, 1); ?>
                                            </span>
                                            <span class="students">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                                <?php echo isset($rek->total_peserta) ? $rek->total_peserta : 0; ?>
                                            </span>
                                            <span class="duration">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                                                    <path d="M12 8v4l3 3"/>
                                                    <circle cx="12" cy="12" r="10"/>
                                                </svg>
                                                <?php echo htmlspecialchars($durasi); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="price-section">
                                            <div class="price <?php echo ($rek->biaya == 0) ? 'gratis' : ''; ?>">
                                                <?php echo $harga_formatted; ?>
                                            </div>
                                            <button class="enroll-btn" onclick="event.stopPropagation(); window.location.href='<?php echo site_url('siswa/pendaftaran/checkout/' . $rek->kursus_id); ?>'">
                                                Daftar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-content">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <h4>Tidak ada rekomendasi</h4>
                                <p>Semua kursus sudah diambil atau Anda telah menyelesaikan semuanya!</p>
                                <a href="<?php echo site_url('siswa/kursus/kursus_saya'); ?>" class="explore-btn">Lihat Kursus Saya</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right Column - Notifikasi -->
                <div class="right-column">
                    <div class="section-card fade-in stagger-delay-2">
                        <div class="section-title">
                            <span>Notifikasi Terbaru</span>
                            <a href="<?php echo site_url('siswa/notifikasi'); ?>" class="see-all">Lihat Semua →</a>
                        </div>
                        
                        <div class="notifikasi-list">
                            <?php if(isset($notifikasi) && !empty($notifikasi)): ?>
                                <?php foreach($notifikasi as $notif): ?>
                                <div class="notifikasi-item <?php echo $notif->status == 'unread' ? 'unread' : ''; ?>">
                                    <div class="notifikasi-text">
                                        <?php echo htmlspecialchars($notif->isi); ?>
                                    </div>
                                    <div class="notifikasi-time">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        <?php 
                                            $date = new DateTime($notif->tanggal);
                                            $now = new DateTime();
                                            $interval = $date->diff($now);
                                            
                                            if($interval->y > 0) {
                                                echo $interval->y . ' tahun yang lalu';
                                            } elseif($interval->m > 0) {
                                                echo $interval->m . ' bulan yang lalu';
                                            } elseif($interval->d > 0) {
                                                echo $interval->d . ' hari yang lalu';
                                            } elseif($interval->h > 0) {
                                                echo $interval->h . ' jam yang lalu';
                                            } elseif($interval->i > 0) {
                                                echo $interval->i . ' menit yang lalu';
                                            } else {
                                                echo 'Baru saja';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-content" style="padding: 20px;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                    </svg>
                                    <h4>Tidak ada notifikasi</h4>
                                    <p style="margin-bottom: 0;">Tidak ada notifikasi baru</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Efek animasi untuk progress bars
            document.querySelectorAll('.progress-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
            
            // Handle image loading errors
            document.querySelectorAll('img[onerror]').forEach(img => {
                img.addEventListener('error', function() {
                    const container = this.parentElement;
                    if (container.classList.contains('kursus-image-container') || 
                        container.classList.contains('rekomendasi-image-container')) {
                        container.innerHTML = '<div class="image-placeholder"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>';
                    }
                });
            });
        });
    </script>
</body>
</html>