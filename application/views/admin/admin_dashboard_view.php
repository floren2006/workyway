<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WorkyWay</title>
    
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
            display: flex;
        }
        
        /* Sidebar Styles */
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
            background-color: #2c3e50;
            color: white;
        }
        
        .sidebar-header h2 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            color: white;
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
        }
        
        .menu-text {
            flex-grow: 1;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .dashboard-header {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .stat-icon.students { background-color: #e3f2fd; color: #1976d2; }
        .stat-icon.teachers { background-color: #f3e5f5; color: #7b1fa2; }
        .stat-icon.lpk { background-color: #e8f5e9; color: #388e3c; }
        .stat-icon.transactions { background-color: #fff3e0; color: #f57c00; }
        .stat-icon.revenue { background-color: #fce4ec; color: #c2185b; }
        .stat-icon.courses { background-color: #e8eaf6; color: #303f9f; }
        .stat-icon.rating { background-color: #fff8e1; color: #ff8f00; }
        .stat-icon.enrollment { background-color: #e0f2f1; color: #00695c; }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 14px;
            color: #718096;
            margin-bottom: 0;
        }
        
        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        /* Recent Activity */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            transition: background-color 0.2s;
        }
        
        .activity-item:hover {
            background-color: #f8f9fa;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }
        
        .activity-icon.user { background-color: #e3f2fd; color: #1976d2; }
        .activity-icon.course { background-color: #f3e5f5; color: #7b1fa2; }
        .activity-icon.transaction { background-color: #fff3e0; color: #f57c00; }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .activity-time {
            font-size: 12px;
            color: #718096;
        }
        
        /* Progress Bar */
        .progress {
            height: 8px;
            border-radius: 4px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-cogs"></i> Panel Admin</h2>
        </div>
        
        <div class="sidebar-menu">
            <a href="<?php echo base_url('admin/dashboard'); ?>" class="menu-item active">
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
            <a href="<?php echo base_url('admin/laporan'); ?>" class="menu-item">
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
        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Dashboard Admin</h1>
                    <p class="mb-0 text-muted">Selamat datang di panel administrasi WorkyWay</p>
                </div>
                <div>
                    <span class="me-3 text-muted">
                        <i class="fas fa-user me-1"></i> 
                        <?php echo isset($session_data['nama']) ? $session_data['nama'] : 'Administrator'; ?>
                    </span>
                    <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon students">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($total_stats['total_siswa']) ? $total_stats['total_siswa'] : 0; ?></div>
                    <p class="stat-label">Total Siswa</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon teachers">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($total_stats['total_guru']) ? $total_stats['total_guru'] : 0; ?></div>
                    <p class="stat-label">Total Guru</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon lpk">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($total_stats['total_lpk']) ? $total_stats['total_lpk'] : 0; ?></div>
                    <p class="stat-label">Total LPK</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon transactions">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($total_stats['total_transaksi']) ? $total_stats['total_transaksi'] : 0; ?></div>
                    <p class="stat-label">Total Transaksi</p>
                </div>
            </div>
        </div>
        
        <!-- Second Row Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($formatted_total_pendapatan) ? $formatted_total_pendapatan : 'Rp 0'; ?></div>
                    <p class="stat-label">Total Pendapatan</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon courses">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($total_stats['kursus_aktif']) ? $total_stats['kursus_aktif'] : 0; ?></div>
                    <p class="stat-label">Kursus Aktif</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon rating">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($formatted_avg_rating) ? $formatted_avg_rating : '0.0'; ?></div>
                    <p class="stat-label">Rating Rata-rata</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon enrollment">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="stat-value"><?php echo isset($total_stats['total_enrollment']) ? $total_stats['total_enrollment'] : 0; ?></div>
                    <p class="stat-label">Total Enrollment</p>
                </div>
            </div>
        </div>
        
        <!-- Monthly Stats and Charts -->
        <div class="row">
            <!-- Monthly Revenue -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i> Pendapatan Bulan Ini</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <h3 class="mb-0"><?php echo isset($formatted_pendapatan_bulan_ini) ? $formatted_pendapatan_bulan_ini : 'Rp 0'; ?></h3>
                            <small class="text-muted">Total Pendapatan <?php echo date('F Y'); ?></small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-success">
                                <i class="fas fa-arrow-up me-1"></i> 
                                <?php echo isset($monthly_stats['transaksi_bulan_ini']) ? $monthly_stats['transaksi_bulan_ini'] : 0; ?> Transaksi
                            </span>
                        </div>
                    </div>
                    <div class="progress">
                        <?php 
                        $progress = isset($monthly_stats['pendapatan_bulan_ini']) && $monthly_stats['pendapatan_bulan_ini'] > 0 ? 70 : 30;
                        ?>
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress; ?>%" 
                             aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            
            <!-- Course Status -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i> Status Kursus</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat-icon courses me-3" style="width: 40px; height: 40px; font-size: 18px;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0"><?php echo isset($total_stats['kursus_aktif']) ? $total_stats['kursus_aktif'] : 0; ?></h4>
                                    <small class="text-muted">Kursus Aktif</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat-icon rating me-3" style="width: 40px; height: 40px; font-size: 18px; background-color: #fff3cd; color: #856404;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0"><?php echo isset($total_stats['kursus_pending']) ? $total_stats['kursus_pending'] : 0; ?></h4>
                                    <small class="text-muted">Pending Review</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?php echo isset($total_stats['kursus_aktif']) && $total_stats['kursus_aktif'] > 0 ? 70 : 30; ?>%" 
                                 aria-valuenow="<?php echo isset($total_stats['kursus_aktif']) ? $total_stats['kursus_aktif'] : 0; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted"><?php echo isset($total_stats['kursus_aktif']) ? $total_stats['kursus_aktif'] : 0; ?> dari <?php echo isset($total_stats['kursus_aktif']) && isset($total_stats['kursus_pending']) ? ($total_stats['kursus_aktif'] + $total_stats['kursus_pending']) : 0; ?> kursus aktif</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="chart-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i> Aktivitas Terbaru</h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshStats()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    
                    <div id="activityList">
                        <!-- Activity items will be loaded here -->
                        <div class="activity-item">
                            <div class="activity-icon user">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Siswa baru mendaftar</div>
                                <div class="activity-time">Baru saja</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon transaction">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Transaksi baru berhasil</div>
                                <div class="activity-time">5 menit yang lalu</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon course">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Kursus baru ditambahkan</div>
                                <div class="activity-time">1 jam yang lalu</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon user">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Guru baru diverifikasi</div>
                                <div class="activity-time">2 jam yang lalu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to refresh stats
        function refreshStats() {
            const refreshBtn = event.currentTarget;
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            refreshBtn.disabled = true;
            
            fetch('<?php echo base_url("admin/dashboard/refresh_stats"); ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update all stats
                        updateStatCard('total_siswa', data.data.total_siswa);
                        updateStatCard('total_guru', data.data.total_guru);
                        updateStatCard('total_lpk', data.data.total_lpk);
                        updateStatCard('total_transaksi', data.data.total_transaksi);
                        updateStatCard('total_pendapatan', data.data.total_pendapatan);
                        updateStatCard('kursus_aktif', data.data.kursus_aktif);
                        updateStatCard('avg_rating', data.data.avg_rating);
                        updateStatCard('total_enrollment', data.data.total_enrollment);
                        
                        // Update monthly stats
                        document.querySelector('.stat-value:nth-child(2)').textContent = data.data.pendapatan_bulan_ini;
                        document.querySelector('.badge.bg-success').innerHTML = `<i class="fas fa-arrow-up me-1"></i> ${data.data.transaksi_bulan_ini} Transaksi`;
                        
                        // Add new activity item
                        addActivityItem('Stats updated', 'System');
                        
                        // Update timestamp
                        document.querySelector('.activity-time:last-child').textContent = `Updated: ${data.data.timestamp}`;
                    }
                    
                    // Reset button
                    setTimeout(() => {
                        refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                        refreshBtn.disabled = false;
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                    refreshBtn.disabled = false;
                });
        }
        
        function updateStatCard(statName, value) {
            // Find and update specific stat cards
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const parentCard = stat.closest('.stat-card');
                if (parentCard) {
                    const icon = parentCard.querySelector('.stat-icon i');
                    if (icon) {
                        if (icon.classList.contains('fa-user-graduate') && statName === 'total_siswa') {
                            stat.textContent = value;
                        } else if (icon.classList.contains('fa-chalkboard-teacher') && statName === 'total_guru') {
                            stat.textContent = value;
                        } else if (icon.classList.contains('fa-university') && statName === 'total_lpk') {
                            stat.textContent = value;
                        } else if (icon.classList.contains('fa-exchange-alt') && statName === 'total_transaksi') {
                            stat.textContent = value;
                        } else if (icon.classList.contains('fa-money-bill-wave') && statName === 'total_pendapatan') {
                            stat.textContent = value;
                        } else if (icon.classList.contains('fa-book') && statName === 'kursus_aktif') {
                            stat.textContent = value;
                        } else if (icon.classList.contains('fa-star') && statName === 'avg_rating') {
                            stat.textContent = value;
                        } else if (icon.classList.contains('fa-clipboard-check') && statName === 'total_enrollment') {
                            stat.textContent = value;
                        }
                    }
                }
            });
        }
        
        function addActivityItem(title, user) {
            const activityList = document.getElementById('activityList');
            const now = new Date();
            const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            const activityItem = document.createElement('div');
            activityItem.className = 'activity-item';
            activityItem.innerHTML = `
                <div class="activity-icon user">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${title}</div>
                    <div class="activity-time">${timeString} - by ${user}</div>
                </div>
            `;
            
            // Add at the top
            activityList.insertBefore(activityItem, activityList.firstChild);
            
            // Keep only 5 items
            const items = activityList.querySelectorAll('.activity-item');
            if (items.length > 5) {
                activityList.removeChild(items[items.length - 1]);
            }
        }
        
        // Auto refresh every 30 seconds
        setInterval(() => {
            const refreshBtn = document.querySelector('.btn-outline-primary');
            if (!refreshBtn.disabled) {
                refreshStats();
            }
        }, 30000);
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add timestamp to last activity
            const lastActivity = document.querySelector('.activity-time:last-child');
            if (lastActivity) {
                lastActivity.textContent = `Updated: <?php echo date('H:i:s'); ?>`;
            }
        });
    </script>
</body>
</html>