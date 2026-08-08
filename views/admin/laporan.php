<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Panel Admin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: white;
            min-height: 100vh;
            border-right: 1px solid #dee2e6;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            background-color: white;
            color: #333;
        }
        
        .sidebar-header h2 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            color: #333;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 12px 20px;
            color: #495057;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            margin: 2px 0;
        }
        
        .menu-item:hover {
            background-color: #f0f7ff;
            color: #007bff;
        }
        
        .menu-item.active {
            background-color: #e3f2fd;
            color: #007bff;
            font-weight: 500;
        }
        
        .menu-item i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .menu-text {
            flex-grow: 1;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        /* Header dengan Logout */
        .dashboard-header {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .header-title p {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 5px 0 0 0;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            text-align: center;
            position: relative;
        }
        
        .stat-card h3 {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin: 10px 0;
        }
        
        .stat-trend {
            font-size: 0.9rem;
            color: #28a745;
            font-weight: 500;
        }
        
        .stat-trend.negative {
            color: #dc3545;
        }
        
        .stat-period {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 0.75rem;
            color: #adb5bd;
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 12px;
        }
        
        /* Content Sections */
        .content-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #6c757d;
        }
        
        /* Tables */
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .report-table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        .report-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            color: #333;
        }
        
        .report-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .report-table .text-success {
            color: #28a745;
            font-weight: 600;
        }
        
        /* Top Instructors */
        .instructors-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        
        @media (max-width: 992px) {
            .instructors-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .instructor-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .instructor-card:hover {
            border-color: #c7d2fe;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .instructor-rank {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            text-align: center;
            line-height: 32px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .instructor-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .instructor-stats {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 15px;
        }
        
        .instructor-revenue {
            font-size: 1.25rem;
            font-weight: 700;
            color: #10b981;
        }
        
        /* Download Button */
        .download-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            color: white;
            text-decoration: none;
        }
        
        /* Footer */
        .dashboard-footer {
            margin-top: 50px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Panel Admin</h2>
        </div>
        
        <div class="sidebar-menu">
            <a href="<?php echo base_url('admin/dashboard'); ?>" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>
            <a href="<?php echo base_url('admin/manajemen_pengguna'); ?>" class="menu-item">
                <i class="fas fa-users"></i>
                <span class="menu-text">Management Pengguna</span>
            </a>
            <a href="<?php echo base_url('admin/manajemen_kursus'); ?>" class="menu-item">
                <i class="fas fa-book-open"></i>
                <span class="menu-text">Management Kursus</span>
            </a>
            <a href="<?php echo base_url('admin/manajemen_transaksi'); ?>" class="menu-item">
                <i class="fas fa-exchange-alt"></i>
                <span class="menu-text">Management Transaksi</span>
            </a>
            <a href="<?php echo base_url('admin/laporan'); ?>" class="menu-item active">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-text">Laporan & Statistik</span>
            </a>
            <a href="<?php echo base_url('admin/pengaturan'); ?>" class="menu-item">
                <i class="fas fa-cog"></i>
                <span class="menu-text">Pengaturan</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header dengan Logout -->
        <div class="dashboard-header">
            <div class="header-title">
                <h1>Laporan & Statistik</h1>
                <p>Analisis kinerja platform secara menyeluruh</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo base_url('admin/laporan/download'); ?>" class="download-btn">
                    <i class="fas fa-download"></i> Download Laporan
                </a>
                <span class="text-muted">
                    <i class="fas fa-user me-1"></i> Admin
                </span>
                <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Statistik Overview -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-chart-line"></i> Statistik Overview
                <span class="stat-period">30 Hari Terakhir</span>
            </h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Pengguna Aktif</h3>
                    <div class="stat-value"><?php echo number_format($overview_stats['pengguna_aktif']); ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i> ↑ <?php echo $overview_stats['pertumbuhan_pengguna']; ?>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Kursus Populer</h3>
                    <div class="stat-value"><?php echo number_format($overview_stats['kursus_populer']); ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i> ↑ <?php echo $overview_stats['pertumbuhan_kursus']; ?>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Pendapatan Total</h3>
                    <div class="stat-value">Rp <?php echo $formatted_revenue; ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i> ↑ <?php echo $overview_stats['pertumbuhan_pendapatan']; ?>
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Rating Platform</h3>
                    <div class="stat-value"><?php echo number_format($overview_stats['rating_platform'], 1); ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i> <?php echo $overview_stats['pertumbuhan_rating']; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pertumbuhan Bulanan -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-chart-bar"></i> Pertumbuhan Bulanan
            </h2>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Users</th>
                        <th>Courses</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthly_growth as $month): ?>
                    <tr>
                        <td><?php echo $month['bulan']; ?></td>
                        <td><?php echo number_format($month['users']); ?></td>
                        <td><?php echo number_format($month['courses']); ?></td>
                        <td class="text-success">Rp <?php echo number_format($month['revenue'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Top Instruktur -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-crown"></i> Top Instruktur
            </h2>
            
            <div class="instructors-grid">
                <?php $rank = 1; ?>
                <?php foreach ($top_instructors as $instructor): ?>
                <div class="instructor-card">
                    <div class="instructor-rank">#<?php echo $rank++; ?></div>
                    <div class="instructor-name"><?php echo $instructor->nama_instruktur; ?></div>
                    <div class="instructor-stats">
                        <?php echo $instructor->jumlah_kursus; ?> kursus • <?php echo $instructor->jumlah_siswa; ?> siswa
                    </div>
                    <div class="instructor-revenue">
                        Rp <?php echo number_format($instructor->total_pendapatan, 0, ',', '.'); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Performa Kategori -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-chart-pie"></i> Performa Kategori
            </h2>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Kursus</th>
                        <th>Total Siswa</th>
                        <th>Rating Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($category_performance as $category): ?>
                    <tr>
                        <td><?php echo $category->nama_kategori; ?></td>
                        <td><?php echo number_format($category->jumlah_kursus); ?></td>
                        <td><?php echo number_format($category->total_siswa); ?></td>
                        <td><?php echo number_format($category->rating_rata_rata, 1); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="dashboard-footer">
            <p>© <?php echo date('Y'); ?> Panel Admin - Sistem Kursus Online</p>
            <p>Laporan dihasilkan pada: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>