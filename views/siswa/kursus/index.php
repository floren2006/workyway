<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajahi Kursus - WorkyWay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: #ffffff;
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
        }

        /* Navbar Styles */
        .navbar {
            background: white;
            padding: 20px 40px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
        .kursus-content {
            padding: 40px;
            min-height: calc(100vh - 120px);
        }

        /* Header Section */
        .header-section {
            margin-bottom: 40px;
        }

        .header-section h1 {
            color: #1e293b;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header-section p {
            color: #64748b;
            font-size: 18px;
            margin-bottom: 24px;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 600px;
            margin-bottom: 40px;
        }

        .search-input {
            width: 100%;
            padding: 16px 52px 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .search-input:focus {
            outline: none;
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.1);
        }

        .search-button {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: #4f46e5;
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background: #4338ca;
            transform: translateY(-50%) scale(1.05);
        }

        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
        }

        /* Filter Sidebar */
        .filter-sidebar {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
        }

        .filter-section {
            margin-bottom: 32px;
        }

        .filter-section h3 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .filter-option:hover {
            background: #f1f5f9;
        }

        .filter-option input[type="radio"] {
            accent-color: #4f46e5;
            width: 18px;
            height: 18px;
        }

        .filter-option label {
            color: #475569;
            font-size: 14px;
            cursor: pointer;
            flex: 1;
        }

        .filter-option.active {
            background: #ede9fe;
            color: #4f46e5;
            font-weight: 500;
        }

        .filter-option.active label {
            color: #4f46e5;
            font-weight: 500;
        }

        .filter-price {
            border-top: 1px solid #e2e8f0;
            padding-top: 24px;
        }

        /* Courses Grid */
        .courses-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .courses-header h2 {
            color: #1e293b;
            font-size: 20px;
            font-weight: 600;
        }

        .courses-count {
            color: #64748b;
            font-size: 14px;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        /* Course Card */
        .course-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .course-card:hover {
            border-color: #c7d2fe;
            box-shadow: 0 10px 40px rgba(79, 70, 229, 0.15);
            transform: translateY(-4px);
        }

        .course-card.popular::before {
            content: 'Paling Populer';
            position: absolute;
            top: -10px;
            right: 20px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 1;
        }

        /* Course Image Section */
        .course-image-container {
            width: 100%;
            height: 250;
            margin-bottom: 16px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background: #f1f5f9;
        }

        .course-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .course-card:hover .course-image {
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
            width: 48px;
            height: 48px;
            opacity: 0.5;
        }

        .course-category {
            display: inline-block;
            background: #ede9fe;
            color: #4f46e5;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 16px;
        }

        .course-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .course-instructor {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .course-meta {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .course-rating, .course-students {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            font-size: 14px;
        }

        .course-rating {
            color: #f59e0b;
            font-weight: 500;
        }

        .course-price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .course-price {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        .course-price.gratis {
            color: #10b981;
        }

        .enroll-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .enroll-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .enroll-btn.enrolled {
            background: #10b981;
        }

        .enroll-btn.enrolled:hover {
            background: #0da271;
            transform: none;
            box-shadow: none;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .no-results h3 {
            font-size: 20px;
            margin-bottom: 12px;
        }

        .no-results p {
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
            
            .filter-sidebar {
                position: sticky;
                top: 20px;
                z-index: 100;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 20px;
                padding: 15px;
            }
            
            .navbar-menu ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
            
            .kursus-content {
                padding: 20px;
            }
            
            .courses-grid {
                grid-template-columns: 1fr;
            }
            
            .search-container {
                max-width: 100%;
            }
            
            .course-image-container {
                height: 160px;
            }
        }

        @media (max-width: 480px) {
            .navbar-menu ul {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
            
            .filter-sidebar {
                padding: 15px;
            }
            
            .course-card {
                padding: 20px;
            }
            
            .course-image-container {
                height: 140px;
            }
        }

        /* Loading Animation */
        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: calc(200px + 100%) 0;
            }
        }

        .loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* Image loading placeholder */
        .image-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>WorkyWay</h2>
            </div>
            <div class="navbar-menu">
                <ul>
                    <li><a href="<?php echo site_url('siswa/dashboard'); ?>">Dashboard</a></li>
                    <li class="active"><a href="<?php echo site_url('siswa/kursus'); ?>">Kursus</a></li>
                    <li><a href="<?php echo site_url('siswa/profil'); ?>">Profil</a></li>
                    <li><a href="<?php echo site_url('login/logout'); ?>">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="kursus-content">
            <!-- Header -->
            <div class="header-section">
                <h1>Jelajahi Kursus</h1>
                <p>Temukan kursus terbaik untuk mengembangkan skillmu</p>
                
                <!-- Search Bar -->
                <form method="GET" action="<?php echo site_url('siswa/kursus'); ?>" class="search-container">
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Cari kursus..." 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    <button type="submit" class="search-button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Main Layout -->
            <div class="main-layout">
                <!-- Filter Sidebar -->
                <div class="filter-sidebar">
                    <div class="filter-section">
                        <h3>Filter</h3>
                        
                        <!-- Kategori -->
                        <div class="filter-group">
                            <h4>Kategori</h4>
                            <form id="filterForm" method="GET" action="<?php echo site_url('siswa/kursus'); ?>">
                                <div class="filter-option <?php echo (empty($filter_kategori) || $filter_kategori == 'semua') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="kategori_semua" 
                                           name="kategori" 
                                           value="semua" 
                                           <?php echo (empty($filter_kategori) || $filter_kategori == 'semua') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="kategori_semua">Semua</label>
                                </div>
                                
                                <?php if(isset($kategori_list) && $kategori_list): ?>
                                    <?php foreach($kategori_list as $kategori): ?>
                                    <div class="filter-option <?php echo ($filter_kategori == $kategori->nama_kategori) ? 'active' : ''; ?>">
                                        <input type="radio" 
                                               id="kategori_<?php echo $kategori->kategori_id; ?>" 
                                               name="kategori" 
                                               value="<?php echo htmlspecialchars($kategori->nama_kategori); ?>"
                                               <?php echo ($filter_kategori == $kategori->nama_kategori) ? 'checked' : ''; ?>
                                               onchange="this.form.submit()">
                                        <label for="kategori_<?php echo $kategori->kategori_id; ?>">
                                            <?php echo $kategori->nama_kategori; ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <!-- Tingkat Kesulitan -->
                    <div class="filter-section">
                        <h3>Tingkat Kesulitan</h3>
                        <div class="filter-group">
                            <form id="kesulitanForm" method="GET" action="<?php echo site_url('siswa/kursus'); ?>">
                                <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($filter_kategori ?? ''); ?>">
                                <input type="hidden" name="harga" value="<?php echo htmlspecialchars($filter_harga ?? ''); ?>">
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                
                                <div class="filter-option <?php echo (empty($filter_kesulitan) || $filter_kesulitan == 'semua') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="kesulitan_semua" 
                                           name="kesulitan" 
                                           value="semua"
                                           <?php echo (empty($filter_kesulitan) || $filter_kesulitan == 'semua') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="kesulitan_semua">Semua</label>
                                </div>
                                
                                <div class="filter-option <?php echo ($filter_kesulitan == 'pemula') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="kesulitan_pemula" 
                                           name="kesulitan" 
                                           value="pemula"
                                           <?php echo ($filter_kesulitan == 'pemula') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="kesulitan_pemula">Pemula</label>
                                </div>
                                
                                <div class="filter-option <?php echo ($filter_kesulitan == 'menengah') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="kesulitan_menengah" 
                                           name="kesulitan" 
                                           value="menengah"
                                           <?php echo ($filter_kesulitan == 'menengah') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="kesulitan_menengah">Menengah</label>
                                </div>
                                
                                <div class="filter-option <?php echo ($filter_kesulitan == 'lanjutan') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="kesulitan_lanjutan" 
                                           name="kesulitan" 
                                           value="lanjutan"
                                           <?php echo ($filter_kesulitan == 'lanjutan') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="kesulitan_lanjutan">Lanjutan</label>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Filter Harga -->
                    <div class="filter-section filter-price">
                        <h3>Harga</h3>
                        <div class="filter-group">
                            <form id="hargaForm" method="GET" action="<?php echo site_url('siswa/kursus'); ?>">
                                <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($filter_kategori ?? ''); ?>">
                                <input type="hidden" name="kesulitan" value="<?php echo htmlspecialchars($filter_kesulitan ?? ''); ?>">
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                
                                <div class="filter-option <?php echo (empty($filter_harga) || $filter_harga == 'semua') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="harga_semua" 
                                           name="harga" 
                                           value="semua"
                                           <?php echo (empty($filter_harga) || $filter_harga == 'semua') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="harga_semua">Semua</label>
                                </div>
                                
                                <div class="filter-option <?php echo ($filter_harga == 'gratis') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="harga_gratis" 
                                           name="harga" 
                                           value="gratis"
                                           <?php echo ($filter_harga == 'gratis') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="harga_gratis">Gratis</label>
                                </div>
                                
                                <div class="filter-option <?php echo ($filter_harga == '<200') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="harga_kecil" 
                                           name="harga" 
                                           value="<200"
                                           <?php echo ($filter_harga == '<200') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="harga_kecil">&lt; Rp 200.000</label>
                                </div>
                                
                                <div class="filter-option <?php echo ($filter_harga == '200-500') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="harga_menengah" 
                                           name="harga" 
                                           value="200-500"
                                           <?php echo ($filter_harga == '200-500') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="harga_menengah">Rp 200.000 - Rp 500.000</label>
                                </div>
                                
                                <div class="filter-option <?php echo ($filter_harga == '>500') ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           id="harga_besar" 
                                           name="harga" 
                                           value=">500"
                                           <?php echo ($filter_harga == '>500') ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label for="harga_besar">&gt; Rp 500.000</label>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Reset Filter -->
                    <button onclick="resetFilters()" 
                            style="width: 100%; padding: 12px; background: #f1f5f9; color: #64748b; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.3s ease;">
                        Reset Filter
                    </button>
                </div>

                <!-- Courses Content -->
                <div class="courses-content">
                    <div class="courses-header">
                        <h2>Kursus Tersedia</h2>
                        <div class="courses-count">
                            <?php echo isset($total_kursus) ? $total_kursus : '0'; ?> kursus ditemukan
                        </div>
                    </div>

                    <!-- COURSES GRID SECTION -->
                    <?php if(isset($kursus_list) && !empty($kursus_list)): ?>
                        <div class="courses-grid">
                            <?php foreach($kursus_list as $index => $kursus): ?>
                                <?php 
                                    $is_popular = ($kursus->rating_rata2 >= 4.5 && $kursus->total_peserta > 1000);
                                    $is_enrolled = false; // Ini bisa diambil dari database nanti
                                    $harga_formatted = ($kursus->biaya == 0) ? 'Gratis' : 'Rp ' . number_format($kursus->biaya, 0, ',', '.');
                                    
                                    // URL untuk checkout dengan data yang diperlukan
                                    $user_id = isset($siswa) && isset($siswa->user_id) ? $siswa->user_id : 1;
                                    $checkout_url = base_url('siswa/pendaftaran/checkout/' . $kursus->kursus_id . '?user_id=' . $user_id);
                                    
                                    // Path gambar kursus
                                    $gambar_path = !empty($kursus->gambar_kursus) ? base_url('uploads/kursus/' . $kursus->gambar_kursus) : base_url('assets/images/default-course.jpg');
                                    $gambar_available = !empty($kursus->gambar_kursus) && file_exists(FCPATH . 'uploads/kursus/' . $kursus->gambar_kursus);
                                ?>
                                
                                <div class="course-card <?php echo $is_popular ? 'popular' : ''; ?>">
                                    <!-- Gambar Kursus -->
                                    <div class="course-image-container <?php echo !$gambar_available ? 'image-loading' : ''; ?>">
                                        <?php if($gambar_available): ?>
                                            <img src="<?php echo $gambar_path; ?>" 
                                                 alt="<?php echo htmlspecialchars($kursus->judul_kursus); ?>"
                                                 class="course-image"
                                                 onerror="this.onerror=null; this.parentElement.classList.add('image-placeholder'); this.style.display='none';">
                                        <?php else: ?>
                                            <div class="image-placeholder">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <span class="course-category"><?php echo $kursus->nama_kategori; ?></span>
                                    <h3 class="course-title"><?php echo $kursus->judul_kursus; ?></h3>
                                    <p class="course-instructor"><?php echo $kursus->instruktur; ?></p>
                                    
                                    <div class="course-meta">
                                        <span class="course-rating">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#fbbf24" stroke="none">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                            <?php echo number_format($kursus->rating_rata2, 1); ?>
                                        </span>
                                        <span class="course-students">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            <?php echo $kursus->total_peserta; ?>
                                        </span>
                                        <span class="course-duration">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                                                <path d="M12 8v4l3 3"/>
                                                <circle cx="12" cy="12" r="10"/>
                                            </svg>
                                            <?php echo $kursus->durasi; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="course-price-section">
                                        <div class="course-price <?php echo ($kursus->biaya == 0) ? 'gratis' : ''; ?>">
                                            <?php echo $harga_formatted; ?>
                                        </div>
                                        
                                        <!-- TOMBOL LIHAT DETAIL YANG DIARAHKAN KE CHECKOUT -->
                                        <?php if($is_enrolled): ?>
                                            <button class="enroll-btn enrolled" 
                                                    onclick="window.location.href='<?php echo site_url('dashboard'); ?>'">
                                                Lihat Kursus
                                            </button>
                                        <?php else: ?>
                                            <button class="enroll-btn" 
                                                    onclick="window.location.href='<?php echo $checkout_url; ?>'">
                                                Lihat Detail
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-results">
                            <h3>😔 Tidak ada kursus yang ditemukan</h3>
                            <p>Coba ubah filter pencarian Anda atau cari dengan kata kunci lain.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Reset All Filters
        function resetFilters() {
            window.location.href = "<?php echo site_url('siswa/kursus'); ?>";
        }

        // Show Notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #10b981;
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Handle image loading errors
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.course-image');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    const container = this.parentElement;
                    container.classList.remove('image-loading');
                    container.classList.add('image-placeholder');
                    const placeholder = document.createElement('div');
                    placeholder.className = 'image-placeholder';
                    placeholder.innerHTML = `
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    `;
                    container.appendChild(placeholder);
                });
            });
        });

        // Add animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }
            
            .course-image-container {
                animation: fadeIn 0.5s ease;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>