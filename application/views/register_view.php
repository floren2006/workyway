<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card shadow border-0 p-4">
                <h4 class="text-center mb-2 fw-bold">Daftar Akun</h4>
                <p class="text-center text-muted mb-4">
                    Pilih peran Anda untuk melanjutkan
                </p>

                <!-- ROLE: SISWA -->
                <div class="role-option mb-3" onclick="goRegister('siswa')">
                    <div class="d-flex align-items-center">
                        <div class="role-icon me-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Siswa</h6>
                            <small class="text-muted">
                                Saya ingin belajar dan mengikuti kursus
                            </small>
                        </div>
                    </div>
                </div>

                <!-- ROLE: LPK -->
                <div class="role-option" onclick="goRegister('lpk')">
                    <div class="d-flex align-items-center">
                        <div class="role-icon me-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">LPK</h6>
                            <small class="text-muted">
                                Saya mewakili lembaga pelatihan kerja
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- ROLE: GURU -->
                <div class="role-option mb-3" onclick="goRegister('guru')">
                    <div class="d-flex align-items-center">
                        <div class="role-icon me-3">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Guru Freelance</h6>
                            <small class="text-muted">
                                Saya ingin mengajar dan berbagi ilmu
                            </small>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <span class="text-muted">
                        Sudah punya akun?
                        <a href="<?= base_url('login'); ?>" class="fw-semibold">
                            Login
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- STYLE KHUSUS -->
<style>
    .role-option {
        border: 2px solid #dee2e6;
        border-radius: 12px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .role-option:hover {
        border-color: #0d6efd;
        background-color: rgba(13,110,253,0.05);
        transform: translateY(-2px);
    }

    .role-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background-color: rgba(13,110,253,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0d6efd;
        font-size: 18px;
    }
</style>

<!-- JS -->
<script>
function goRegister(role) {
    window.location.href = "<?= base_url('register'); ?>/" + role;
}
</script>
