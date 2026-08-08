<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Management Transaksi</title>
    
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
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
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
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            text-align: center;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .stat-icon.transaction {
            color: #4361ee;
        }
        
        .stat-icon.commission {
            color: #28a745;
        }
        
        .stat-icon.lpk {
            color: #ff6b6b;
        }
        
        .stat-icon.teacher {
            color: #ffc107;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .stat-change {
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .stat-change.positive {
            color: #28a745;
        }
        
        /* Search Box */
        .search-box {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }
        
        .search-box .input-group {
            max-width: 400px;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        
        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .table {
            margin: 0;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 12px 15px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 12px 15px;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .action-btn {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        /* Pagination */
        .pagination-info {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Footer */
        .dashboard-footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        
        /* Modal Styles */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-title {
            font-weight: 600;
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
            <a href="<?php echo base_url('admin/manajemen_transaksi'); ?>" class="menu-item active">
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
        <!-- Header dengan Logout -->
        <div class="dashboard-header">
            <div class="header-title">
                <h1>Management Transaksi</h1>
                <p>Kelola riwayat pembayaran dan komisi platform</p>
            </div>
            <div class="header-actions">
                <span class="text-muted">
                    <i class="fas fa-user me-1"></i> Admin Utama
                </span>
                <a href="#" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon transaction">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">
                    <?php 
                        if(isset($stats->total_transaksi) && $stats->total_transaksi > 0) {
                            $total_millions = $stats->total_transaksi / 1000000;
                            echo 'Rp ' . number_format($total_millions, 1, '.', '') . ' jt';
                        } else {
                            echo 'Rp 0';
                        }
                    ?>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +23%
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon commission">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="stat-label">Komisi Platform</div>
                <div class="stat-value">
                    <?php 
                        if(isset($stats->total_komisi) && $stats->total_komisi > 0) {
                            $komisi_millions = $stats->total_komisi / 1000000;
                            echo 'Rp ' . number_format($komisi_millions, 1, '.', '') . ' jt';
                        } else {
                            echo 'Rp 0';
                        }
                    ?>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +18%
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon lpk">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-label">Pembayaran LPK</div>
                <div class="stat-value">
                    <?php 
                        if(isset($stats->pembayaran_lpk) && $stats->pembayaran_lpk > 0) {
                            $lpk_millions = $stats->pembayaran_lpk / 1000000;
                            echo 'Rp ' . number_format($lpk_millions, 1, '.', '') . ' jt';
                        } else {
                            echo 'Rp 0';
                        }
                    ?>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +25%
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon teacher">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-label">Pembayaran Guru</div>
                <div class="stat-value">
                    <?php 
                        if(isset($stats->pembayaran_guru) && $stats->pembayaran_guru > 0) {
                            $guru_millions = $stats->pembayaran_guru / 1000000;
                            echo 'Rp ' . number_format($guru_millions, 1, '.', '') . ' jt';
                        } else {
                            echo 'Rp 0';
                        }
                    ?>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +20%
                </div>
            </div>
        </div>
        
        <!-- Search Box -->
        <div class="search-box">
            <form method="get" action="<?php echo base_url('admin/manajemen_transaksi'); ?>" class="d-flex align-items-center">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari transaksi..." 
                           value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <a href="<?php echo base_url('admin/manajemen_transaksi/export_excel'); ?>" class="btn btn-success ms-3">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </form>
        </div>
        
        <!-- Table Container -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">Daftar Transaksi</h3>
                <div class="pagination-info">
                    Menampilkan <?php echo (($current_page - 1) * 10) + 1; ?> sampai 
                    <?php echo min($current_page * 10, $total_rows); ?> dari <?php echo $total_rows; ?> hasil
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Siswa</th>
                            <th>Kursus</th>
                            <th>Total</th>
                            <th>Komisi Platform</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($transactions)): ?>
                            <?php foreach($transactions as $transaction): ?>
                            <tr>
                                <td>
                                    <strong>TRX<?php echo str_pad($transaction->transaksi_id, 3, '0', STR_PAD_LEFT); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($transaction->siswa_nama); ?></td>
                                <td><?php echo htmlspecialchars($transaction->judul_kursus); ?></td>
                                <td>
                                    <strong>Rp <?php echo number_format($transaction->jumlah, 0, ',', '.'); ?></strong>
                                </td>
                                <td>
                                    Rp <?php echo number_format($transaction->komisi_platform, 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?php echo date('Y-m-d H:i', strtotime($transaction->tanggal_transaksi)); ?>
                                </td>
                                <td>
                                    <?php 
                                        $status_class = '';
                                        $status_text = '';
                                        switch($transaction->status) {
                                            case 'success':
                                                $status_class = 'status-success';
                                                $status_text = 'Sukses';
                                                break;
                                            case 'pending':
                                                $status_class = 'status-pending';
                                                $status_text = 'Pending';
                                                break;
                                            case 'failed':
                                                $status_class = 'status-failed';
                                                $status_text = 'Gagal';
                                                break;
                                            default:
                                                $status_class = 'status-pending';
                                                $status_text = $transaction->status;
                                        }
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" data-bs-toggle="modal" 
                                            data-bs-target="#editStatusModal<?php echo $transaction->transaksi_id; ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Edit Status Modal -->
                            <div class="modal fade" id="editStatusModal<?php echo $transaction->transaksi_id; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?php echo base_url('admin/manajemen_transaksi/update_status'); ?>" method="post">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Status Transaksi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="transaksi_id" value="<?php echo $transaction->transaksi_id; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">ID Transaksi</label>
                                                    <input type="text" class="form-control" 
                                                           value="TRX<?php echo str_pad($transaction->transaksi_id, 3, '0', STR_PAD_LEFT); ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status Saat Ini</label>
                                                    <input type="text" class="form-control" 
                                                           value="<?php echo $status_text; ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status Baru</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?php echo $transaction->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="success" <?php echo $transaction->status == 'success' ? 'selected' : ''; ?>>Success</option>
                                                        <option value="failed" <?php echo $transaction->status == 'failed' ? 'selected' : ''; ?>>Failed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data transaksi</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        Halaman <?php echo $current_page; ?> dari <?php echo $total_pages; ?>
                    </div>
                    <nav>
                        <?php echo $pagination; ?>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="dashboard-footer">
            <p>© <?php echo date('Y'); ?> Panel Admin - Sistem Kursus Online</p>
            <p>Terakhir diperbarui: <?php echo date('H:i:s'); ?></p>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Success/Error messages
            <?php if($this->session->flashdata('success')): ?>
                alert('<?php echo $this->session->flashdata("success"); ?>');
            <?php endif; ?>
            
            <?php if($this->session->flashdata('error')): ?>
                alert('<?php echo $this->session->flashdata("error"); ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>