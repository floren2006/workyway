<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Statistik Penilaian</h4>
            <p class="text-muted mb-0">Analisis performa penilaian tugas</p>
        </div>
        <div>
            <a href="<?= base_url('lpk/penilaian'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Penilaian
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <div class="icon-shape bg-primary text-white rounded-circle p-3 mx-auto mb-3">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-0">
                        <?php 
                            $total_penilaian = 0;
                            foreach($statistik_kursus as $kursus) {
                                $total_penilaian += $kursus['sudah_dinilai'];
                            }
                            echo $total_penilaian;
                        ?>
                    </h3>
                    <p class="text-muted mb-0">Total Penilaian</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="icon-shape bg-success text-white rounded-circle p-3 mx-auto mb-3">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <?php
                        $total_nilai = 0;
                        $count = 0;
                        foreach($statistik_kursus as $kursus) {
                            if($kursus['rata_rata_nilai'] != null) {
                                $total_nilai += $kursus['rata_rata_nilai'];
                                $count++;
                            }
                        }
                        $rata_global = $count > 0 ? round($total_nilai / $count, 1) : 0;
                    ?>
                    <h3 class="fw-bold mb-0"><?= $rata_global; ?></h3>
                    <p class="text-muted mb-0">Rata-rata Nilai</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <div class="icon-shape bg-info text-white rounded-circle p-3 mx-auto mb-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <?php
                        $total_selesai = 0;
                        $total_tugas = 0;
                        foreach($statistik_kursus as $kursus) {
                            $total_selesai += $kursus['sudah_dinilai'];
                            $total_tugas += $kursus['total_tugas'];
                        }
                        $persentase = $total_tugas > 0 ? round(($total_selesai / $total_tugas) * 100, 1) : 0;
                    ?>
                    <h3 class="fw-bold mb-0"><?= $persentase; ?>%</h3>
                    <p class="text-muted mb-0">Tuntas Dinilai</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="icon-shape bg-warning text-white rounded-circle p-3 mx-auto mb-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <?php
                        $total_belum = 0;
                        foreach($statistik_kursus as $kursus) {
                            $total_belum += $kursus['belum_dinilai'];
                        }
                    ?>
                    <h3 class="fw-bold mb-0"><?= $total_belum; ?></h3>
                    <p class="text-muted mb-0">Perlu Dinilai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Per Kursus -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Per Kursus</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($statistik_kursus)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-pie fa-4x text-muted mb-3"></i>
                            <h5>Belum ada data statistik</h5>
                            <p class="text-muted">Belum ada tugas yang dinilai</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kursus</th>
                                        <th>Total Tugas</th>
                                        <th>Sudah Dinilai</th>
                                        <th>Belum Dinilai</th>
                                        <th>Rata-rata Nilai</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($statistik_kursus as $stat): ?>
                                        <?php 
                                            $progress = $stat['total_tugas'] > 0 ? 
                                                round(($stat['sudah_dinilai'] / $stat['total_tugas']) * 100, 0) : 0;
                                            
                                            $progress_class = '';
                                            if($progress >= 80) $progress_class = 'bg-success';
                                            elseif($progress >= 50) $progress_class = 'bg-warning';
                                            else $progress_class = 'bg-danger';
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?= htmlspecialchars($stat['judul_kursus']); ?></div>
                                                <small class="text-muted">ID: <?= $stat['kursus_id']; ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?= $stat['total_tugas']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success"><?= $stat['sudah_dinilai']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning"><?= $stat['belum_dinilai']; ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= $stat['rata_rata_nilai'] ? round($stat['rata_rata_nilai'], 1) : 'N/A'; ?></div>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar <?= $progress_class; ?>" 
                                                         role="progressbar" 
                                                         style="width: <?= $progress; ?>%"
                                                         aria-valuenow="<?= $progress; ?>" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        <?= $progress; ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Statistik Per Bulan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Penilaian 6 Bulan Terakhir</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($statistik_bulan)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data bulanan</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach($statistik_bulan as $bulan): ?>
                                <?php 
                                    $bulan_nama = date('M Y', strtotime($bulan['bulan'] . '-01'));
                                ?>
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0"><?= $bulan_nama; ?></h6>
                                        <span class="badge bg-primary rounded-pill">
                                            <?= $bulan['total_dinilai']; ?> tugas
                                        </span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted">Rata-rata</small>
                                            <div class="fw-bold"><?= round($bulan['rata_rata_nilai'], 1); ?></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Rentang</small>
                                            <div class="fw-bold">
                                                <?= round($bulan['nilai_terendah'], 1); ?>-<?= round($bulan['nilai_tertinggi'], 1); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Chart Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribusi Nilai</h5>
                </div>
                <div class="card-body">
                    <canvas id="nilaiChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 1rem 1.25rem;
}
.card-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 0;
}
.icon-shape {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.progress {
    border-radius: 10px;
}
.progress-bar {
    border-radius: 10px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.list-group-item {
    border-left: none;
    border-right: none;
}
.list-group-item:first-child {
    border-top: none;
}
.list-group-item:last-child {
    border-bottom: none;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart untuk distribusi nilai
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('nilaiChart').getContext('2d');
    
    // Data contoh - dalam implementasi real, data ini harus diambil dari controller
    const nilaiChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Sangat Baik (81-100)', 'Baik (61-80)', 'Cukup (41-60)', 'Kurang (0-40)'],
            datasets: [{
                data: [35, 40, 20, 5], // Contoh data
                backgroundColor: [
                    '#4caf50', // Hijau
                    '#2196f3', // Biru
                    '#ff9800', // Orange
                    '#f44336'  // Merah
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw + '%';
                            return label;
                        }
                    }
                }
            }
        }
    });
    
    // Fitur export data (opsional)
    document.querySelectorAll('.export-btn').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            exportStatistik(type);
        });
    });
    
    function exportStatistik(type) {
        let data = [];
        
        <?php if(!empty($statistik_kursus)): ?>
            data = <?= json_encode($statistik_kursus); ?>;
        <?php endif; ?>
        
        if(type === 'csv') {
            exportToCSV(data);
        } else if(type === 'pdf') {
            exportToPDF(data);
        }
    }
    
    function exportToCSV(data) {
        let csv = 'Kursus,Total Tugas,Sudah Dinilai,Belum Dinilai,Rata-rata Nilai\n';
        
        data.forEach(row => {
            csv += `"${row.judul_kursus}",${row.total_tugas},${row.sudah_dinilai},${row.belum_dinilai},${row.rata_rata_nilai || 0}\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `statistik_penilaian_${new Date().toISOString().slice(0,10)}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    function exportToPDF(data) {
        alert('Fitur export PDF sedang dalam pengembangan');
    }
});
</script>