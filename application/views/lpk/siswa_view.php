<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Siswa</title>

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
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #374151;
        }
        .progress {
            height: 6px;
            border-radius: 4px;
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-aktif {
            background: #e0f2fe;
            color: #0369a1;
        }
        .badge-lulus {
            background: #dcfce7;
            color: #166534;
        }
        .badge-belum {
            background: #fef9c3;
            color: #854d0e;
        }
        
        /* Responsif */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                max-width: 100%;
                padding: 20px;
            }
        }
        
        .table-responsive {
            max-width: 100%;
            overflow-x: auto;
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
        <h4 class="fw-bold mb-1">Daftar Siswa</h4>
        <p class="text-muted mb-0">Kelola dan pantau siswa yang terdaftar</p>
    </div>

    <!-- SEARCH & FILTER -->
    <form method="get" class="mb-4">
        <!-- BAR ATAS -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <!-- SEARCH BESAR -->
            <input type="text"
                   name="search"
                   class="form-control form-control-lg flex-grow-1"
                   placeholder="Cari siswa..."
                   value="<?= htmlspecialchars($this->input->get('search') ?? ''); ?>">

            <!-- BUTTON FILTER -->
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#filterCollapse">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>

        <!-- FILTER DROPDOWN -->
        <div class="collapse" id="filterCollapse">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- FILTER KURSUS -->
                        <div class="col-md-5">
                            <select name="kursus_id" class="form-select">
                                <option value="">Semua Kursus</option>
                                <?php foreach ($kursus as $k): ?>
                                    <option value="<?= $k['kursus_id']; ?>"
                                        <?= $this->input->get('kursus_id') == $k['kursus_id'] ? 'selected' : ''; ?>>
                                        <?= $k['judul_kursus']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- FILTER INSTRUKTUR -->
                        <div class="col-md-5">
                            <select name="instruktur_id" class="form-select">
                                <option value="">Semua Instruktur</option>
                                <?php foreach ($instruktur as $i): ?>
                                    <option value="<?= $i['instruktur_id']; ?>"
                                        <?= $this->input->get('instruktur_id') == $i['instruktur_id'] ? 'selected' : ''; ?>>
                                        <?= $i['nama']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- BUTTON APPLY -->
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- CARD TABLE -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nama Siswa</th>
                            <th>Kursus</th>
                            <th>Progress</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($siswa)): ?>
                        <?php foreach ($siswa as $s): ?>
                            <?php
                                $progress = $s['nilai'] !== null ? (int)$s['nilai'] : 0;
                                if ($progress >= 100) {
                                    $status = 'Lulus';
                                    $badge  = 'badge-lulus';
                                } elseif ($progress > 0) {
                                    $status = 'Aktif';
                                    $badge  = 'badge-aktif';
                                } else {
                                    $status = 'Belum Lulus';
                                    $badge  = 'badge-belum';
                                }
                            ?>
                            <tr>
                                <!-- NAMA -->
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar">
                                            <?= strtoupper(substr($s['nama_siswa'], 0, 1)); ?>
                                        </div>
                                        <span class="fw-medium"><?= $s['nama_siswa']; ?></span>
                                    </div>
                                </td>

                                <!-- KURSUS -->
                                <td class="text-muted"><?= $s['judul_kursus']; ?></td>

                                <!-- PROGRESS -->
                                <td style="width:200px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar bg-primary"
                                                 style="width: <?= $progress; ?>%">
                                            </div>
                                        </div>
                                        <small class="text-muted"><?= $progress; ?>%</small>
                                    </div>
                                </td>

                                <!-- STATUS -->
                                <td>
                                    <span class="badge-status <?= $badge; ?>"><?= $status; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada siswa terdaftar
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>