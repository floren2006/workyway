<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kursus</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        .main-content {
            margin-left: 260px;
            padding: 32px;
            max-width: calc(100vw - 260px);
            box-sizing: border-box;
        }
        .card-stat {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin: 8px 0;
        }
        .stat-change {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .stat-change.up {
            color: #059669;
        }
        .stat-change.down {
            color: #dc2626;
        }
        .card-header-custom {
            background: none;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 24px;
        }
        .feedback-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            background: white;
        }
        .rating-stars {
            color: #fbbf24;
        }
        .star-filled {
            color: #fbbf24;
        }
        .star-empty {
            color: #d1d5db;
        }
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                max-width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<?php $this->load->view('lpk/sidebar'); ?>

<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Laporan Kursus</h4>
        <p class="text-muted mb-0">Analisis statistik dan performa kursus</p>
    </div>

    <!-- STATISTIK SUMMARY -->
    <div class="row g-4 mb-4">
        <!-- Total Peserta -->
        <div class="col-md-4">
            <div class="card card-stat p-4">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Peserta</p>
                    <h2 class="stat-number"><?= number_format($summary['total_peserta']); ?></h2>
                        <?php
                        $percent = $peserta_growth['percent'];
                        $is_up = $percent >= 0;
                        ?>

                    <div class="stat-change <?= $is_up ? 'up' : 'down' ?>">
                        <i class="bi <?= $is_up ? 'bi-arrow-up' : 'bi-arrow-down' ?>"></i>
                        <span><?= abs($percent); ?>% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- Nilai Rata-rata -->
        <div class="col-md-4">
            <div class="card card-stat p-4">
                <div class="card-body">
                    <p class="text-muted mb-2">Nilai Rata-rata</p>
                    <?php
                    $nilai_up = $nilai_growth >= 0;
                    ?> 
                    <h2 class="stat-number"><?= number_format($summary['avg_nilai'], 2); ?></h2>
                    <div class="stat-change <?= $nilai_up ? 'up' : 'down' ?>">
                        <i class="bi <?= $nilai_up ? 'bi-arrow-up' : 'bi-arrow-down' ?>"></i>
                        <span><?= abs($nilai_growth); ?>% dari bulan lalu</span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Rating Rata-rata -->
        <div class="col-md-4">
            <div class="card card-stat p-4">
                <div class="card-body">
                    <p class="text-muted mb-2">Rating Rata-rata</p>
                    <?php
                    $rating_up = $rating_growth >= 0;
                    ?> 
                    <h2 class="stat-number"><?= number_format($summary['avg_rating'], 2); ?></h2>
                    <div class="stat-change <?= $rating_up ? 'up' : 'down' ?>">
                        <i class="bi <?= $rating_up ? 'bi-arrow-up' : 'bi-arrow-down' ?>"></i>
                        <span><?= abs($rating_growth); ?> dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL STATISTIK PER KURSUS -->
    <div class="card card-stat mb-4">
        <div class="card-header card-header-custom">
            <h6 class="fw-bold mb-0">Statistik Per Kursus</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-muted fw-normal">Nama Kursus</th>
                            <th class="text-muted fw-normal">Peserta</th>
                            <th class="text-muted fw-normal">Nilai Rata-rata</th>
                            <th class="text-muted fw-normal">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistik as $s): ?>
                        <tr>
                            <td>
                                <div class="fw-medium"><?= $s['judul_kursus']; ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= number_format($s['peserta']); ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= number_format($s['avg_nilai'], 0); ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rating-stars">
                                        <?php 
                                        $rating = $s['avg_rating'];
                                        $fullStars = floor($rating);
                                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                        
                                        for ($i = 0; $i < $fullStars; $i++): ?>
                                            <i class="bi bi-star-fill star-filled"></i>
                                        <?php endfor; ?>
                                        
                                        <?php if ($hasHalfStar): ?>
                                            <i class="bi bi-star-half star-filled"></i>
                                        <?php endif; ?>
                                        
                                        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                            <i class="bi bi-star star-empty"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="fw-semibold"><?= number_format($rating, 1); ?></span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- UMPAN BALIK TERBARU -->
    <div class="card card-stat">
        <div class="card-header card-header-custom">
            <h6 class="fw-bold mb-0">Umpan Balik Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <?php foreach ($ulasan as $u): ?>
                <div class="col-md-6">
                    <div class="feedback-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-bold mb-1"><?= $u['nama_siswa']; ?></h6>
                                <p class="text-muted mb-0"><?= $u['judul_kursus']; ?></p>
                            </div>
                            <div class="rating-stars">
                                <?php 
                                $rating = $u['rating'];
                                for ($i = 1; $i <= 5; $i++): 
                                    if ($i <= $rating): ?>
                                        <i class="bi bi-star-fill star-filled"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star star-empty"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">"<?= $u['review']; ?>"</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>