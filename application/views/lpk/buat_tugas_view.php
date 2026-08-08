<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel LPK - Buat Tugas Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        
        /* HEADER */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 5px;
        }
        
        .page-title p {
            color: #7f8c8d;
            margin: 0;
        }
        
        /* FORM */
        .form-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .form-header {
            background: #f8f9fa;
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
        }
        
        .form-header h3 {
            margin: 0;
            color: #000000;
            font-size: 18px;
            font-weight: 600;
        }
        
        .form-body {
            padding: 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .required:after {
            content: " *";
            color: #e74c3c;
        }
        
        .form-control, .form-select {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .form-text {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        /* ACTION BUTTONS */
        .form-actions {
            padding: 20px 25px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        
        .btn-cancel {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-cancel:hover {
            background: #7f8c8d;
            color: white;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.2);
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #27ae60, #219653);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(46, 204, 113, 0.3);
        }
        
        /* INFO PANEL */
        .info-panel {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        
        .info-header {
            background: #f8f9fa;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
        }
        
        .info-header h4 {
            margin: 0;
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
        }
        
        .info-body {
            padding: 20px 25px;
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-header h1,
            .sidebar-header p,
            .menu-title,
            .menu-item span {
                display: none;
            }
            
            .menu-item {
                text-align: center;
                padding: 15px 10px;
            }
            
            .menu-item i {
                margin: 0;
                font-size: 18px;
            }
            
            .main-content {
                margin-left: 70px;
                padding: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .form-body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- PAGE HEADER -->
            <div class="page-header">
                <div class="page-title">
                    <h1>Buat Tugas Baru</h1>
                    <p>Tambahkan tugas baru untuk siswa kursus</p>
                </div>
            </div>
            
            <!-- FORM BUAT TUGAS -->
            <div class="form-container">
                <div class="form-header">
                    <h3>Form Tambah Tugas</h3>
                    <p class="text-muted mb-0">Isi form berikut untuk menambahkan tugas baru</p>
                </div>
                
                <div class="form-body">
                    <!-- Alert Messages -->
                    <div class="alert alert-success alert-dismissible fade show d-none" role="alert">
                        Tugas berhasil ditambahkan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    
                    <?php if (validation_errors()): ?>
                        <div class="alert alert-danger">
                            <?= validation_errors(); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('lpk/tugas/store'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                             <div class="col-md-6 mb-4">
            <label class="form-label required">Kursus</label>
            <select name="kursus_id" id="kursus_id" class="form-select" required 
                    onchange="loadMateriByKursus(this.value)">
                <option value="">Pilih Kursus</option>
                <?php foreach ($kursus as $k): ?>
                    <option value="<?= $k['kursus_id']; ?>">
                        <?= htmlspecialchars($k['judul_kursus']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Pilih kursus terlebih dahulu untuk memfilter materi</div>
        </div>

        <!-- Materi (akan di-update via AJAX berdasarkan kursus yang dipilih) -->
        <div class="col-md-6 mb-4">
            <label class="form-label required">Materi</label>
            <select name="materi_id" id="materi_id" class="form-select" required>
                <option value="">Pilih Materi</option>
                <?php foreach ($materi as $m): ?>
                    <option value="<?= $m['materi_id']; ?>">
                        <?= htmlspecialchars($m['judul_materi']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Pilih materi dari kursus yang dipilih</div>
        </div>

        <!-- Judul Tugas -->
        <div class="col-md-12 mb-4">
            <label class="form-label required">Judul Tugas</label>
            <input type="text" name="judul_tugas" class="form-control" 
                   placeholder="Contoh: Membuat Halaman Web Sederhana" required>
            <div class="form-text">Maksimal 255 karakter</div>
        </div>

                            <!-- Tipe Tugas -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label required">Tipe Tugas</label>
                                <select name="tipe_tugas" class="form-select" required>
                                    <option value="">Pilih Tipe Tugas</option>
                                    <option value="individual">Tugas Individu</option>
                                    <option value="kelompok">Tugas Kelompok</option>
                                </select>
                            </div>

                            <!-- Deadline -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label required">Deadline</label>
                                <input type="datetime-local" name="deadline" class="form-control" required>
                                <div class="form-text">Batas waktu pengumpulan tugas</div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label required">Deskripsi Tugas</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required
                                          placeholder="Jelaskan secara detail tentang tugas yang akan diberikan kepada siswa"></textarea>
                                <div class="form-text">Deskripsikan tujuan, langkah-langkah, dan kriteria penilaian</div>
                            </div>

                            <!-- File Template (Opsional) -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Template Tugas (Opsional)</label>
                                <input type="file" name="file_template" class="form-control"
                                    accept=".zip,.pdf,.doc,.docx">
                                <div class="form-text">
                                    File pendukung tugas (ZIP, PDF, DOC, DOCX). Maksimal 50MB.
                                </div>
                            </div>

                            <!-- Max Score -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label required">Nilai Maksimal</label>
                                <div class="input-group">
                                    <input type="number" name="max_score" class="form-control" 
                                           value="100" min="1" max="100" required>
                                    <span class="input-group-text">/100</span>
                                </div>
                                <div class="form-text">Nilai maksimal yang bisa didapat</div>
                            </div>
                        </div>
                        
                        <!-- ACTION BUTTONS -->
                        <div class="form-actions">
                            <a href="<?= base_url('lpk/tugas'); ?>" class="btn btn-cancel">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save me-1"></i> Simpan Tugas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- INFO PANEL -->
            <div class="info-panel">
                <div class="info-header">
                    <h4><i class="fas fa-info-circle me-2"></i> Panduan Membuat Tugas</h4>
                </div>
                <div class="info-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-check-circle text-success me-2"></i> Yang harus dilakukan:</h6>
                            <ul class="mb-0">
                                <li>Judul harus jelas dan deskriptif</li>
                                <li>Pilih materi yang sesuai dengan tugas</li>
                                <li>Berikan deskripsi yang lengkap dan jelas</li>
                                <li>Set deadline yang realistis</li>
                                <li>Tentukan nilai maksimal yang sesuai</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-exclamation-triangle text-warning me-2"></i> Yang dihindari:</h6>
                            <ul class="mb-0">
                                <li>Judul yang ambigu atau terlalu pendek</li>
                                <li>Deskripsi yang tidak lengkap</li>
                                <li>Deadline yang terlalu dekat</li>
                                <li>File template yang terlalu besar</li>
                                <li>Instruksi yang membingungkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum datetime for deadline (today)
            const now = new Date();
            const minDateTime = now.toISOString().slice(0, 16);
            const deadlineInput = document.querySelector('input[name="deadline"]');
            if (deadlineInput) {
                deadlineInput.min = minDateTime;
                deadlineInput.value = minDateTime;
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Sidebar menu active state
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>

    <script>
// Fungsi untuk memuat materi berdasarkan kursus yang dipilih
function loadMateriByKursus(kursus_id) {
    const materiSelect = document.getElementById('materi_id');
    
    if (!kursus_id) {
        materiSelect.innerHTML = '<option value="">Pilih Materi</option>';
        materiSelect.disabled = true;
        return;
    }
    
    // Tampilkan loading
    materiSelect.innerHTML = '<option value="">Memuat materi...</option>';
    materiSelect.disabled = true;
    
    // AJAX request untuk mengambil materi
    fetch(`<?= base_url('lpk/tugas/get_materi_by_kursus/'); ?>${kursus_id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let options = '<option value="">Pilih Materi</option>';
                
                data.materi.forEach(materi => {
                    options += `<option value="${materi.materi_id}">${materi.judul_materi}</option>`;
                });
                
                materiSelect.innerHTML = options;
                materiSelect.disabled = false;
            } else {
                materiSelect.innerHTML = '<option value="">Tidak ada materi ditemukan</option>';
                materiSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            materiSelect.innerHTML = '<option value="">Error memuat materi</option>';
            materiSelect.disabled = false;
        });
}

// Set kursus pertama sebagai pilihan default saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    const kursusSelect = document.getElementById('kursus_id');
    if (kursusSelect && kursusSelect.options.length > 1) {
        // Pilih opsi pertama yang bukan "Pilih Kursus"
        kursusSelect.selectedIndex = 1;
        loadMateriByKursus(kursusSelect.value);
    }
});
</script>
<script>
document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('materi_id').disabled = false;
});
</script>

</body>
</html>