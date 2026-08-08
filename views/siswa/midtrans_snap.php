<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Kursus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-xxxxx"></script>

    <style>
        body {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.2);
            padding: 32px;
            text-align: center;
        }

        .card h2 {
            color: #1e293b;
            margin-bottom: 8px;
        }

        .card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .amount {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 30px;
        }

        .btn-pay {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37,99,235,.4);
        }

        .secure {
            margin-top: 18px;
            font-size: 13px;
            color: #64748b;
        }

        .secure span {
            color: #16a34a;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Konfirmasi Pembayaran</h2>
    <p>Silakan lanjutkan pembayaran kursus Anda</p>

    <div class="amount">
        Rp <?= number_format($total ?? 0, 0, ',', '.') ?>
    </div>


    <button id="pay-button" class="btn-pay">
        💳 Bayar Sekarang
    </button>

    <div class="secure">
        <span>🔒 Aman</span> • Diproses oleh Midtrans
    </div>
</div>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('<?= $snapToken ?>', {
       onSuccess: function(result){
        let metode = result.payment_type;

        if (result.payment_type === 'bank_transfer' && result.va_numbers) {
            metode = 'VA ' + result.va_numbers[0].bank.toUpperCase();
        }

        // KIRIM KE SERVER VIA AJAX
        fetch("<?= site_url('siswa/midtrans/update_metode') ?>", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                enrollment_id: <?= $enrollment_id ?>,
                metode: metode
            })
        }).then(() => {
            window.location.href =
            "<?= site_url('siswa/midtrans/success/'.$enrollment_id) ?>";
        });
    },


        onPending: function(result){
            alert("⏳ Menunggu pembayaran diselesaikan");
        },
        onError: function(result){
            alert("❌ Pembayaran gagal");
        }
    });
};
</script>

</body>
</html>
