<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - Panel Admin</title>
    
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
            margin-bottom: 20px;
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
        
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        /* Search and Filter */
        .search-filter {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 5px 15px;
            border-radius: 20px;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .filter-btn:hover {
            background: #f8f9fa;
        }
        
        .filter-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        /* Table */
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            padding: 12px 15px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 12px 15px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #6c757d;
            font-weight: bold;
        }
        
        .user-details {
            flex-grow: 1;
        }
        
        .user-name {
            font-weight: 500;
            color: #333;
            margin-bottom: 2px;
        }
        
        .user-email {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        /* Status badges */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-aktif {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-nonaktif {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        
        /* Role badges */
        .role-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .role-siswa {
            background-color: #e3f2fd;
            color: #1565c0;
        }
        
        .role-guru {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .role-lpk {
            background-color: #fff3e0;
            color: #ef6c00;
        }
        
        /* Pagination */
        .pagination-container {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        /* Footer */
        .dashboard-footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            padding-top: 15px;
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
            <a href="<?php echo base_url('admin/manajemen_pengguna'); ?>" class="menu-item active">
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
        <!-- Header dengan Logout -->
        <div class="dashboard-header">
            <div class="header-title">
                <h1>Manajemen Pengguna</h1>
                <p>Kelola semua pengguna platform (Siswa, LPK, dan Guru)</p>
            </div>
            <div class="header-actions">
                <span class="text-muted">
                    <i class="fas fa-user me-1"></i> <?php echo $this->session->userdata('admin_name'); ?>
                </span>
                <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-value"><?php echo number_format($total_users); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-label">Siswa</div>
                <div class="stat-value"><?php echo number_format($total_siswa); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-label">LPK</div>
                <div class="stat-value"><?php echo number_format($total_lpk); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-label">Guru Freelance</div>
                <div class="stat-value"><?php echo number_format($total_guru); ?></div>
            </div>
        </div>
        
        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="row">
                <div class="col-md-8">
                    <form method="GET" action="<?php echo base_url('admin/manajemen_pengguna'); ?>">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari pengguna berdasarkan nama atau email..." value="<?php echo $this->input->get('search'); ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                    
                    <div class="filter-buttons">
                        <button type="button" class="filter-btn <?php echo ($this->input->get('filter') == '' || $this->input->get('filter') == 'all') ? 'active' : ''; ?>" data-filter="all">Semua</button>
                        <button type="button" class="filter-btn <?php echo $this->input->get('filter') == 'siswa' ? 'active' : ''; ?>" data-filter="siswa">Siswa</button>
                        <button type="button" class="filter-btn <?php echo $this->input->get('filter') == 'lpk' ? 'active' : ''; ?>" data-filter="lpk">LPK</button>
                        <button type="button" class="filter-btn <?php echo $this->input->get('filter') == 'guru' ? 'active' : ''; ?>" data-filter="guru">Guru</button>
                        <button type="button" class="filter-btn <?php echo $this->input->get('filter') == 'aktif' ? 'active' : ''; ?>" data-filter="aktif">Status Aktif</button>
                        <button type="button" class="filter-btn <?php echo $this->input->get('filter') == 'pending' ? 'active' : ''; ?>" data-filter="pending">Status Pending</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Tanggal Bergabung</th>
                            <th>Kursus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($users)): ?>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <?php 
                                            $initials = '';
                                            $name_parts = explode(' ', $user['nama']);
                                            if(count($name_parts) > 1) {
                                                $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
                                            } else {
                                                $initials = strtoupper(substr($user['nama'], 0, 2));
                                            }
                                            echo $initials;
                                            ?>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name"><?php echo htmlspecialchars($user['nama']); ?></div>
                                            <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $role_class = '';
                                    $role_text = '';
                                    switch($user['role']) {
                                        case 'siswa':
                                            $role_class = 'role-siswa';
                                            $role_text = 'Siswa';
                                            break;
                                        case 'guru':
                                            $role_class = 'role-guru';
                                            $role_text = 'Guru Freelance';
                                            break;
                                        case 'lpk':
                                            $role_class = 'role-lpk';
                                            $role_text = 'LPK';
                                            break;
                                    }
                                    ?>
                                    <span class="role-badge <?php echo $role_class; ?>"><?php echo $role_text; ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    $status_text = '';
                                    
                                    if($user['role'] == 'guru') {
                                        $status = isset($user['guru_status']) ? $user['guru_status'] : 'pending';
                                        if($status == 'approved') {
                                            $status_class = 'status-aktif';
                                            $status_text = 'Aktif';
                                        } elseif($status == 'pending') {
                                            $status_class = 'status-pending';
                                            $status_text = 'Pending';
                                        } else {
                                            $status_class = 'status-nonaktif';
                                            $status_text = 'Nonaktif';
                                        }
                                    } elseif($user['role'] == 'lpk') {
                                        $status = isset($user['lpk_status']) ? $user['lpk_status'] : 'pending';
                                        if($status == 'approved') {
                                            $status_class = 'status-aktif';
                                            $status_text = 'Aktif';
                                        } elseif($status == 'pending') {
                                            $status_class = 'status-pending';
                                            $status_text = 'Pending';
                                        } else {
                                            $status_class = 'status-nonaktif';
                                            $status_text = 'Nonaktif';
                                        }
                                    } else {
                                        $status = isset($user['status_aktif']) ? $user['status_aktif'] : 0;
                                        if($status == 1) {
                                            $status_class = 'status-aktif';
                                            $status_text = 'Aktif';
                                        } else {
                                            $status_class = 'status-nonaktif';
                                            $status_text = 'Nonaktif';
                                        }
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($user['tanggal_daftar'])); ?></td>
                                <td>
                                    <strong><?php echo isset($user['jumlah_kursus']) ? $user['jumlah_kursus'] : 0; ?></strong> kursus
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data pengguna ditemukan</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if(isset($pagination)): ?>
        <div class="pagination-container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        Menampilkan 
                        <strong><?php echo $start; ?></strong> sampai 
                        <strong><?php echo $end; ?></strong> dari 
                        <strong><?php echo $total_rows; ?></strong> hasil
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="dashboard-footer">
            <p>© <?php echo date('Y'); ?> Panel Admin - Sistem Kursus Online</p>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functionality dengan form submission
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                const form = document.querySelector('form');
                const filterInput = document.createElement('input');
                
                // Hapus input filter yang lama jika ada
                const existingFilter = form.querySelector('input[name="filter"]');
                if (existingFilter) {
                    existingFilter.remove();
                }
                
                // Tambahkan input filter baru jika bukan 'all'
                if (filter !== 'all') {
                    filterInput.type = 'hidden';
                    filterInput.name = 'filter';
                    filterInput.value = filter;
                    form.appendChild(filterInput);
                }
                
                // Reset page ke 1 saat filter berubah
                const existingPage = form.querySelector('input[name="page"]');
                if (existingPage) {
                    existingPage.remove();
                }
                
                const pageInput = document.createElement('input');
                pageInput.type = 'hidden';
                pageInput.name = 'page';
                pageInput.value = '1';
                form.appendChild(pageInput);
                
                // Submit form
                form.submit();
            });
        });
        
        // Set active filter button berdasarkan URL parameter saat load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const filterParam = urlParams.get('filter');
            
            // Semua tombol filter sudah di-set active di PHP,
            // tapi kita bisa pastikan lagi di JavaScript jika perlu
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            if (filterParam) {
                const activeBtn = document.querySelector(`.filter-btn[data-filter="${filterParam}"]`);
                if (activeBtn) {
                    activeBtn.classList.add('active');
                }
            } else {
                const allBtn = document.querySelector('.filter-btn[data-filter="all"]');
                if (allBtn) {
                    allBtn.classList.add('active');
                }
            }
        });
        
        // Pastikan pagination links mempertahankan parameter filter dan search
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Biarkan link bekerja normal
                // Tidak perlu manipulasi tambahan karena parameter sudah ada di URL
            });
        });
    </script>
</body>
</html>