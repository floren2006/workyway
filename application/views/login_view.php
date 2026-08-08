<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow p-4">
                <h4 class="text-center mb-4"><strong>Selamat Datang</strong></h4>
                <p class="text-center text-muted mb-4">Masuk ke akun kursus Anda</p>

                <!-- Form login yang mengarah ke fungsi process -->
                <form method="post" action="<?= base_url('login/process'); ?>">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                        value="<?= set_value('email'); ?>"placeholder="Masukkan email Anda" required>
                        <span class="text-danger small"><?= form_error('email'); ?></span>
                    </div>
                    

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Masukkan password" required>
                        <span class="text-danger small"><?php echo form_error('password'); ?></span>
                    </div>

                    <button type="submit" class="btn w-100 text-white" style="background-color: #4F46E5;">
                        Masuk
                    </button>
                </form>
                
                <div class="register-link mt-3 text-center">
                    <span>Belum punya akun? <a href="<?php echo base_url('register'); ?>"><strong>Daftar Sekarang</strong></a></span>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php if ($this->session->flashdata('pesan_sukses')): ?>
    <script>
        swal("Sukses!", "<?php echo $this->session->flashdata('pesan_sukses'); ?>", "success")
        .then(() => {
            // Redirect setelah sweetalert ditutup (opsional)
        });
    </script>
<?php endif ?>

<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <script>
        swal("Gagal!", "<?php echo $this->session->flashdata('pesan_gagal'); ?>", "error");
    </script>
<?php endif ?>

<!-- JavaScript untuk validasi form sebelum submit -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        const username = document.querySelector('input[name="username"]').value;
        const password = document.querySelector('input[name="password"]').value;
        
        if (!username || !password) {
            event.preventDefault();
            swal("Peringatan!", "Harap isi semua field yang diperlukan", "warning");
        }
    });
});
</script>