<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard LPK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background:#f6f8fc;
    font-family:'Segoe UI', sans-serif;
}

.sidebar {
    width:260px;
    height:100vh;
    background:#fff;
    position:fixed;
    padding:20px;
    box-shadow:0 0 15px rgba(0,0,0,.05);
}

.sidebar h5 {
    font-weight:700;
    color:#2563eb;
    margin-bottom:30px;
}

.sidebar a {
    display:block;
    padding:12px 15px;
    color:#444;
    border-radius:8px;
    text-decoration:none;
    margin-bottom:5px;
}

.sidebar a.active,
.sidebar a:hover {
    background:#eaf1ff;
    color:#2563eb;
}

.main {
    margin-left:280px;
    padding:30px;
}

.card-stat {
    border:none;
    border-radius:15px;
    padding:20px;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
}
.icon-box {
    width:50px;
    height:50px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:22px;
    margin-bottom:10px;
}
</style>
</head>
<body>

<?php $this->load->view('lpk/sidebar', ['menu' => 'dashboard']); ?>

<!-- MAIN -->
<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Dashboard</h3>
            <p class="text-muted">Selamat datang di sistem manajemen LPK</p>
        </div>
        <a href="<?= base_url('logout'); ?>" class="btn btn-primary">Logout</a>
    </div>

    <!-- STAT CARD -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card-stat">
                <div class="icon-box bg-primary"><i class="fa fa-book"></i></div>
                <small>Kursus Aktif</small>
                <h4><?= $kursus_aktif ?></h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat">
                <div class="icon-box bg-success"><i class="fa fa-users"></i></div>
                <small>Total Peserta</small>
                <h4><?= number_format($total_peserta) ?></h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat">
                <div class="icon-box bg-warning"><i class="fa fa-star"></i></div>
                <small>Rating Rata-rata</small>
                <h4><?= $rating ?></h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat">
                <div class="icon-box bg-purple" style="background:#8b5cf6">
                    <i class="fa fa-dollar-sign"></i>
                </div>
               <small>Total Pendaftaran</small>
                <h4><?= number_format($total_pendaftaran) ?></h4>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="row g-4">

        <!-- KIRI : KURSUS POPULER -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">
                     Kursus Populer
                </div>

                <div class="card-body">
                    <?php if (!empty($kursus_populer)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($kursus_populer as $k): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= $k->judul_kursus ?>
                                    <span class="badge bg-primary rounded-pill">
                                        <?= $k->total_peserta ?> Peserta
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">Belum ada kursus populer</p>
                    <?php endif; ?>
                </div>
        </div>
    </div>

    <!-- KANAN : STATISTIK PENDAPATAN -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-chart-line text-success"></i> Statistik Pendaftaran
        </div>

        <!-- FILTER TAHUN -->
        <form method="get">
            <select name="tahun" class="form-select form-select-sm"
                    onchange="this.form.submit()">
                <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                    <option value="<?= $i ?>" <?= $i == $tahun ? 'selected' : '' ?>>
                        <?= $i ?>
                    </option>
                <?php endfor; ?>
            </select>
        </form>
    </div>

    <div class="card-body">
        <?php
        $max = max(array_column($stat_pendaftaran, 'total')) ?: 1;
        ?>

        <?php foreach ($stat_pendaftaran as $row): ?>
            <div class="d-flex align-items-center mb-3">
                <div style="width:90px;" class="text-muted">
                    <?= $row['bulan'] ?>
                </div>

                <div class="progress flex-grow-1 mx-3" style="height:12px;">
                    <div class="progress-bar bg-primary"
                         role="progressbar"
                         style="width: <?= ($row['total'] / $max) * 100 ?>%">
                    </div>
                </div>

                <div style="width:40px;" class="fw-bold text-end">
                    <?= $row['total'] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

    </div>


</div>

</div>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
Highcharts.chart('chartPendapatan', {
    chart: {
        type: 'line'
    },
    title: { text: null },

    xAxis: {
        categories: <?= $bulan ?>
    },

    yAxis: {
        title: {
            text: 'Jumlah Pendaftaran'
        },
        allowDecimals: false
    },

    tooltip: {
        shared: true,
        valueSuffix: ' Pendaftaran'
    },

    series: [{
        name: 'Pendaftaran <?= $tahun ?>',
        data: <?= $pendaftaran ?>,
        color: '#198754'
    }],

    credits: { enabled: false }
});
</script>



</body>
</html>
