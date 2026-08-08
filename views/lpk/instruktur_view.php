<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Instruktur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f6f8fc;
            font-family: 'Segoe UI', sans-serif;
        }
        :root {
            --sidebar-width: 260px;
        }
        .main {
            margin-left: 280px;
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
        }
        .instruktur-card {
            min-width: 280px;
            max-width: 280px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,.08);
            transition: transform 0.3s ease;
        }
        .instruktur-card:hover {
            transform: translateY(-5px);
        }
        .instruktur-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3b82f6;
        }
        .badge-kursus {
            background: #dcfce7;
            color: #166534;
            font-size: 12px;
        }
        .badge-siswa {
            background: #dbeafe;
            color: #1e40af;
            font-size: 12px;
        }
        .badge-aktif {
            background: #dcfce7;
            color: #166534;
        }
        .badge-nonaktif {
            background: #fee2e2;
            color: #991b1b;
        }
        .icon-btn {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .scroll-container {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding: 20px 10px;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        
            width: calc(100vw - var(--sidebar-width) - 60px);
            max-width: 100%;
            box-sizing: border-box;
        }
        .scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .scroll-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        .scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }
        .stat-label {
            font-size: 12px;
            color: #64748b;
        }
        .card-header-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 20px;
        }
        
    </style>
</head>
<body>

<?php $this->load->view('lpk/sidebar', ['menu' => $menu]); ?>

<div class="main">

    <!-- Notifikasi -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Manajemen Instruktur</h3>
            <p class="text-muted mb-0">Total <?= count($instruktur); ?> instruktur aktif</p>
        </div>
        <a href="<?= base_url('lpk/instruktur/tambah'); ?>" class="btn btn-primary">
            + Tambah Instruktur
        </a>
    </div>

<!-- DAFTAR INSTRUKTUR - HORIZONTAL SCROLL -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Daftar Instruktur</h5>
        
        <?php if (!empty($instruktur)): ?>
            <div class="scroll-container" id="scrollContainer">
                <?php foreach ($instruktur as $ins): ?>
                <div class="instruktur-card">
                    <div class="card-header-bg text-center position-relative">
                        <div class="d-flex justify-content-center align-items-center gap-3">

                            <!-- FOTO -->
                            <?php if (!empty($ins['foto'])): ?>
                                <img src="<?= base_url('uploads/profilLPK/instruktur/'.$ins['foto']); ?>" 
                                    class="instruktur-img"
                                    alt="<?= $ins['nama']; ?>">
                            <?php else: ?>
                                <div class="instruktur-img bg-light d-flex align-items-center justify-content-center">
                                    <span class="fs-4 text-muted"><?= substr($ins['nama'], 0, 1); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- AKSI -->
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('lpk/instruktur/edit/'.$ins['instruktur_id']); ?>"
                                class="icon-btn bg-white border"
                                title="Edit">
                                    <img src="<?= base_url('assets/image/icon/edit.png'); ?>" width="14">
                                </a>

                                <a href="<?= base_url('lpk/instruktur/hapus/'.$ins['instruktur_id']); ?>"
                                class="icon-btn bg-white border"
                                title="Hapus"
                                onclick="return confirm('Hapus instruktur ini?')">
                                    <img src="<?= base_url('assets/image/icon/hapus.png'); ?>" width="14">
                                </a>
                            </div>

                        </div>

                        <h5 class="fw-bold mt-3 mb-1"><?= $ins['nama']; ?></h5>
                        <p class="mb-2"><?= $ins['keahlian']; ?></p>

                        <?php if ($ins['status'] == 'aktif'): ?>
                            <span class="badge badge-aktif px-3 py-1">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-nonaktif px-3 py-1">Nonaktif</span>
                        <?php endif; ?>

                    </div>

                    
                    <div class="card-body">
                        <!-- Daftar kursus yang diajar -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-2">Kursus yang Diajar:</h6>
                            <?php if (!empty($ins['kursus_diajar'])): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($ins['kursus_diajar'] as $kursus_diajar): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 border-0">
                                            <span class="text-truncate" title="<?= $kursus_diajar['judul_kursus']; ?>">
                                                <?= substr($kursus_diajar['judul_kursus'], 0, 20); ?>
                                                <?= strlen($kursus_diajar['judul_kursus']) > 20 ? '...' : ''; ?>
                                            </span>
                        
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small">Belum ada kursus</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <div class="text-center">
                                <div class="stat-number"><?= $ins['jumlah_kursus'] ?? 0; ?></div>
                                <div class="stat-label">Kursus</div>
                            </div>
                            <div class="text-center">
                                <div class="stat-number"><?= $ins['jumlah_siswa'] ?? 0; ?></div>
                                <div class="stat-label">Siswa</div>
                            </div>
                        </div>
                    
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-3 text-muted small">
                Geser ke kanan atau kiri untuk melihat lebih banyak
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <img src="<?= base_url('assets/image/icon/user-empty.png'); ?>" width="64" class="mb-3">
                <h5 class="text-muted">Belum ada instruktur</h5>
                <p class="text-muted small">Tambahkan instruktur pertama Anda</p>
            </div>
        <?php endif; ?>
    </div>
</div>


   <!-- PENUGASAN INSTRUKTUR KE KURSUS -->
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Penugasan Instruktur ke Kursus</h5>
        
        <form action="<?= base_url('lpk/instruktur/tugaskan'); ?>" method="post">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Pilih Instruktur</label>
                    <select name="instruktur_id" class="form-select" required>
                        <option value="">-- Pilih Instruktur --</option>
                        <?php if (!empty($instruktur)): ?>
                            <?php foreach ($instruktur as $ins): ?>
                                <?php if (isset($ins['status']) && $ins['status'] == 'aktif'): ?>
                                    <option value="<?= isset($ins['instruktur_id']) ? $ins['instruktur_id'] : ''; ?>">
                                        <?= isset($ins['nama']) ? htmlspecialchars($ins['nama']) : ''; ?> 
                                        (<?= isset($ins['keahlian']) ? htmlspecialchars($ins['keahlian']) : ''; ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-5 mb-3">
                    <label class="form-label">Pilih Kursus</label>
                    <select name="kursus_id" class="form-select" required>
                        <option value="">-- Pilih Kursus --</option>
                        <?php if (!empty($kursus) && is_array($kursus)): ?>
                            <?php foreach ($kursus as $k): ?>
                                <?php if (isset($k['kursus_id']) && isset($k['judul_kursus'])): ?>
                                    <option value="<?= $k['kursus_id']; ?>">
                                        <?= htmlspecialchars($k['judul_kursus']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Tidak ada kursus tersedia</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        Tugaskan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Smooth scroll dengan mouse wheel pada container horizontal
document.querySelector('.scroll-container').addEventListener('wheel', (e) => {
    if (e.deltaY !== 0) {
        e.preventDefault();
        e.currentTarget.scrollLeft += e.deltaY;
    }
});

// Tombol navigasi (opsional, jika ingin tombol)
const scrollContainer = document.querySelector('.scroll-container');
const scrollAmount = 300;

function scrollLeft() {
    scrollContainer.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
    });
}

function scrollRight() {
    scrollContainer.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
}

// Tambah tombol navigasi jika diperlukan
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.scroll-container');
    if (container.scrollWidth > container.clientWidth) {
        // Jika konten lebih lebar dari container, tambah tombol navigasi
        const navHTML = `
            <div class="scroll-nav d-flex justify-content-between mt-3">
                <button class="btn btn-outline-primary btn-sm" onclick="scrollLeft()">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-outline-primary btn-sm" onclick="scrollRight()">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>

        `;
        container.parentNode.insertAdjacentHTML('afterend', navHTML);
    }
});
</script>
</body>
</html>