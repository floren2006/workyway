<?php
// Pastikan helper URL dimuat di controller atau autoload
// Jika belum, tambahkan di controller: $this->load->helper('url');
// Atau di autoload.php: $autoload['helper'] = array('url');

// Asumsi data dikirim dari controller:
// $kursus: objek dari database
// $biaya: objek dengan rincian biaya
// $instruktur: string nama instruktur
// $tanggal_mulai: string tanggal yang sudah diformat
// $siswa: objek data siswa

// Fungsi helper untuk format rupiah
function format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Default values untuk mencegah error jika ada data yang kosong
$kursus_nama = isset($kursus->nama) ? $kursus->nama : 'Pengembangan Web dengan React & TypeScript';
$kursus_kode = isset($kursus->kode) ? $kursus->kode : 'WEB-RT-2023';
$instruktur_nama = isset($instruktur) ? $instruktur : 'Dr. Budi Santoso';
$durasi = isset($kursus->durasi) ? $kursus->durasi : '8 minggu (24 jam)';
$tanggal_mulai_formatted = isset($tanggal_mulai) ? $tanggal_mulai : '15 September 2023';

// Biaya - akses sebagai objek
$biaya_kursus = isset($biaya->biaya_kursus) ? $biaya->biaya_kursus : 1500000;
$pajak = isset($biaya->pajak) ? $biaya->pajak : 150000;
$biaya_platform = isset($biaya->biaya_platform) ? $biaya->biaya_platform : 50000;
$total = isset($biaya->total) ? $biaya->total : 1700000;

// Data siswa untuk Midtrans
$siswa_nama = isset($siswa->nama) ? $siswa->nama : 'Nama Pengguna';
$siswa_email = isset($siswa->email) ? $siswa->email : 'email@example.com';
$siswa_telepon = isset($siswa->telepon) ? $siswa->telepon : '08123456789';

// Base URL untuk menghindari error site_url()
// Jika base_url() tidak berfungsi, kita bisa gunakan konfigurasi manual
$base_url = "http://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kursus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.5;
            padding: 0;
            max-width: 800px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            margin: 30px 20px;
            overflow: hidden;
            border: 1px solid #eaeaea;
        }
        
        .header {
            background-color: white;
            padding: 32px 40px 24px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .header h1 {
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        
        .header p {
            color: #666;
            font-size: 16px;
            margin-bottom: 0;
        }
        
        .content {
            padding: 32px 40px 40px;
        }
        
        .section {
            margin-bottom: 32px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .course-title {
            font-size: 22px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .course-code {
            font-size: 16px;
            font-weight: 500;
            color: #1a1a1a;
            background-color: #f8f9fa;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            text-align: center;
            min-width: 150px;
        }
        
        .course-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #1a1a1a;
        }
        
        .price-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 24px;
            margin-top: 8px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .price-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .price-label {
            font-size: 16px;
            color: #666;
        }
        
        .price-value {
            font-size: 16px;
            font-weight: 500;
            color: #1a1a1a;
        }
        
        .total-row {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-top: 8px;
        }
        
        .policy-list {
            padding-left: 20px;
            margin-top: 12px;
        }
        
        .policy-list li {
            margin-bottom: 10px;
            color: #666;
        }
        
        .payment-methods {
            margin-top: 20px;
            color: #666;
        }
        
        .payment-note {
            margin-top: 12px;
            font-size: 15px;
            color: #666;
        }
        
        .button-container {
            margin-top: 40px;
            padding-top: 32px;
            border-top: 1px solid #eaeaea;
        }
        
        .btn-payment {
            display: block;
            width: 100%;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 18px 24px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            text-align: center;
            text-decoration: none;
        }
        
        .btn-payment:hover {
            background-color: #1d4ed8;
        }
        
        .note {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-top: 16px;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 16px;
            }
            
            .header {
                padding: 24px 24px 20px;
            }
            
            .content {
                padding: 24px;
            }
            
            .course-header {
                flex-direction: column;
                gap: 16px;
            }
            
            .course-code {
                align-self: flex-start;
            }
        }
        
        /* Tambahan untuk styling teks bold seperti pada gambar */
        strong {
            font-weight: 600;
        }
        
        .bold-text {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pendaftaran Kursus</h1>
            <p>Silakan periksa detail kursus sebelum melanjutkan</p>
        </div>
        
        <div class="content">
            <div class="section">
                <h2 class="section-title">Ringkasan Kursus</h2>
                
                <div class="course-header">
                    <div>
                        <h3 class="course-title"><?php echo htmlspecialchars($kursus_nama); ?></h3>
                    </div>
                    <div class="course-code">
                        <strong><?php echo htmlspecialchars($kursus_kode); ?></strong>
                    </div>
                </div>
                
                <div class="course-info">
                    <div class="info-item">
                        <span class="info-label">Instruktur</span>
                        <span class="info-value"><?php echo htmlspecialchars($instruktur_nama); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Durasi</span>
                        <span class="info-value"><?php echo htmlspecialchars($durasi); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Mulai</span>
                        <span class="info-value"><?php echo htmlspecialchars($tanggal_mulai_formatted); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">Rincian Biaya</h2>
                
                <div class="price-details">
                    <div class="price-row">
                        <span class="price-label">Harga Kursus</span>
                        <span class="price-value"><?php echo format_rupiah($biaya_kursus); ?></span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Pajak (PPN 10%)</span>
                        <span class="price-value"><?php echo format_rupiah($pajak); ?></span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Biaya Platform</span>
                        <span class="price-value"><?php echo format_rupiah($biaya_platform); ?></span>
                    </div>
                    <div class="price-row total-row">
                        <span class="price-label bold-text">Total Biaya</span>
                        <span class="price-value bold-text"><?php echo format_rupiah($total); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">Kebijakan Pembatalan</h2>
                <ul class="policy-list">
                    <li>Pembatalan dapat dilakukan maksimal 7 hari sebelum tanggal mulai kursus dengan pengembalian dana 100%.</li>
                    <li>Pembatalan kurang dari 7 hari sebelum kursus akan dikenakan biaya administrasi sebesar 25% dari total biaya. Tidak ada pengembalian dana untuk pembatalan pada hari kursus dimulai.</li>
                </ul>
            </div>
            
            <div class="section">
                <h2 class="section-title">Metode Pembayaran</h2>
                <div class="payment-methods">
                    <p>Kami menerima pembayaran melalui transfer bank, kartu kredit, dan e-wallet (GoPay, OVO, DANA).</p>
                    <p class="payment-note">Detail pembayaran akan dikirimkan setelah Anda melanjutkan ke halaman pembayaran.</p>
                </div>
            </div>
            
            <div class="button-container">
                <!-- Form akan mengarah ke controller yang menghandle Midtrans -->
                <form action="<?php echo rtrim($base_url, '/') . '/siswa/midtrans/process'; ?>" method="POST" id="paymentForm">
                    <!-- Data yang akan dikirim ke Midtrans -->
                    <input type="hidden" name="kursus_id" value="<?= $kursus->kursus_id ?>">
                    <input type="hidden" name="kursus_nama" value="<?php echo htmlspecialchars($kursus_nama); ?>">
                    <input type="hidden" name="kode_kursus" value="<?php echo htmlspecialchars($kursus_kode); ?>">
                    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                    <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($siswa_nama); ?>">
                    <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($siswa_email); ?>">
                    <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($siswa_telepon); ?>">
                    <input type="hidden" name="enrollment_id" value="<?= $enrollment_id ?>">
                    
                    <button type="submit" class="btn-payment">Lanjut ke Pembayaran</button>
                </form>
                <p class="note">Dengan mengklik tombol di atas, Anda akan diarahkan ke halaman pembayaran Midtrans</p>
            </div>
        </div>
    </div>

    <script>
        // Validasi form sebelum submit
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                // Konfirmasi sebelum submit
                if (!confirm('Anda akan diarahkan ke halaman pembayaran Midtrans. Lanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>
</html>