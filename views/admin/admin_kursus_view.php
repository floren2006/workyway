<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kursus - Admin Panel</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Basic styles - simpan di sini */
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
        
        /* Table Styles */
        .table th {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
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
            <a href="<?php echo base_url('admin/dashboard'); ?>" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>
            <a href="<?php echo base_url('admin/manajemen_pengguna'); ?>" class="menu-item">
                <i class="fas fa-users"></i>
                <span class="menu-text">Management Pengguna</span>
            </a>
            <a href="<?php echo base_url('admin/manajemen_kursus'); ?>" class="menu-item active">
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
                    <h1>Manajemen Kursus</h1>
                    <p class="mb-0 text-muted">Kelola semua kursus yang tersedia di sistem</p>
                </div>
                <div>
                    <span class="me-3 text-muted">
                        <i class="fas fa-user me-1"></i> <?php echo $this->session->userdata('nama') ?: 'Admin'; ?>
                    </span>
                    <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Search Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?php echo base_url('admin/manajemen_kursus'); ?>" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Cari kursus..." 
                               value="<?php echo isset($search_keyword) ? htmlspecialchars($search_keyword) : ''; ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3><?php echo isset($stats['total_courses']) ? $stats['total_courses'] : 0; ?></h3>
                        <p class="text-muted mb-0">Total Kursus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3><?php echo isset($stats['active_courses']) ? $stats['active_courses'] : 0; ?></h3>
                        <p class="text-muted mb-0">Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3><?php echo isset($stats['pending_courses']) ? $stats['pending_courses'] : 0; ?></h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3><?php echo isset($stats['inactive_courses']) ? $stats['inactive_courses'] : 0; ?></h3>
                        <p class="text-muted mb-0">Non-Aktif</p>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <!-- Courses Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Kursus</h5>
            </div>
            <div class="card-body">
                <?php if(!empty($courses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Judul Kursus</th>
                                    <th>Kategori</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($courses as $course): ?>
                                <tr>
                                    <td>#<?php echo $course['kursus_id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($course['judul_kursus']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($course['nama_guru'] ?? $course['nama_lembaga'] ?? '-'); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($course['nama_kategori']); ?></td>
                                    <td>Rp <?php echo number_format($course['biaya'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?php 
                                            $status_class = '';
                                            if($course['status_kursus'] == 'active') $status_class = 'status-active';
                                            if($course['status_kursus'] == 'pending') $status_class = 'status-pending';
                                            if($course['status_kursus'] == 'inactive') $status_class = 'status-inactive';
                                        ?>
                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <?php 
                                                if($course['status_kursus'] == 'active') echo 'Aktif';
                                                elseif($course['status_kursus'] == 'pending') echo 'Pending';
                                                else echo 'Non-Aktif';
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <?php if($course['status_kursus'] == 'pending'): ?>
                                                <a href="<?php echo base_url('admin/manajemen_kursus/approve/' . $course['kursus_id']); ?>" 
                                                   class="btn btn-success" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="<?php echo base_url('admin/manajemen_kursus/reject/' . $course['kursus_id']); ?>" 
                                                   class="btn btn-warning" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo base_url('admin/manajemen_kursus/hapus/' . $course['kursus_id']); ?>" 
                                               class="btn btn-danger" title="Hapus" 
                                               onclick="return confirm('Yakin ingin menghapus kursus ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada data kursus ditemukan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        
        <!-- Categories Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tags"></i> Daftar Kategori</h5>
            </div>

            <!-- Action Buttons -->
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                <i class="fas fa-plus-circle"></i> Tambah Kategori
            </button>
            <button class="btn btn-success ms-2">
                <i class="fas fa-plus-circle"></i> Tambah Kursus
            </button>
        </div>
            <div class="card-body">
                <?php if(!empty($categories)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kategori</th>
                                    <th>Jumlah Kursus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($categories as $category): ?>
                                <tr>
                                    <td>#<?php echo $category['kategori_id']; ?></td>
                                    <td><?php echo htmlspecialchars($category['nama_kategori']); ?></td>
                                    <td><span class="badge bg-primary"><?php echo $category['jumlah_kursus']; ?></span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-warning" 
                                                    onclick="editCategory(<?php echo $category['kategori_id']; ?>, '<?php echo htmlspecialchars(addslashes($category['nama_kategori'])); ?>', '<?php echo htmlspecialchars(addslashes($category['deskripsi'])); ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?php echo base_url('admin/manajemen_kursus/hapus_kategori/' . $category['kategori_id']); ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada data kategori</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modals -->
    <!-- Tambah Kategori Modal -->
    <div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('admin/manajemen_kursus/tambah_kategori'); ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Kategori Modal -->
    <div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editKategoriForm" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_kategori_id" name="kategori_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Category Function
        function editCategory(id, nama, deskripsi) {
            document.getElementById('edit_kategori_id').value = id;
            document.getElementById('edit_nama_kategori').value = nama;
            document.getElementById('edit_deskripsi').value = deskripsi;
            
            // Update form action
            document.getElementById('editKategoriForm').action = '<?php echo base_url('admin/manajemen_kursus/update_kategori/'); ?>' + id;
            
            // Show modal
            const editModal = new bootstrap.Modal(document.getElementById('editKategoriModal'));
            editModal.show();
        }
        
        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>