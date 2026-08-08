   <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center min-vh-90">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Tingkatkan Keahlian Anda dengan Kursus Online</h1>
                    <p class="lead mb-4">Pelajari keterampilan baru dari para ahli terbaik.</br>Ribuan kursus tersedia untuk membantu Anda</br>mencapai tujuan karir.</br></p>
                    <div class="d-flex gap-3">
                        <a href="#courses" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-play-circle me-2"></i>Mulai Belajar
                        </a>
                        <a href="#categories" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-book me-2"></i>Lihat Kursus
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/image/lp1.jpg" 
                         class="img-fluid rounded shadow-lg" alt="Hero Image">
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Courses Section -->
    <section class="courses-section py-5 bg-light" id="courses">
        <div class="container">
            <div class="row mb-5">
                <div class="col md-8 text-center">
                <h2 class="fw-bold mb-3">Kursus Populer</h2>
                <p class="text-muted">
                    Pilih dari ribuan kursus berkualitas tinggi
                </p>
                </div>
            </div>
            
            <div class="row g-6">
                <?php foreach($popular_courses as $course): ?>
                <div class="col-lg-3 col-md-5">
                    <div class="card course-card h-100">
                        <!-- Bagian atas kartu dengan ikon -->
                        <div class="card-img-top course-thumbnail">
                        <img 
                        src="<?= base_url('uploads/kursus/' . ($course->thumbnail ?? 'default.jpg')); ?>" 
                        alt="<?= html_escape($course->judul_kursus); ?>"
                        class="img-fluid w-100 h-100">
                        </div>
                       
                        <div class="card-body">
                          <!-- Kategori -->
                        <span class="course-category" style="color: #2563EB;">
                            <?php echo $course->nama_kategori ?? 'Umum'; ?>
                        </span>

                        <h5 class="card-title">
                            <?php echo html_escape($course->judul_kursus); ?>
                        </h5>

                        <div class="course-meta-row">

                        <!-- Jumlah siswa -->
                        <div class="student-info">
                            <img 
                                src="<?php echo base_url('assets/image/frame.png'); ?>" 
                                alt="Siswa"
                                class="student-icon"
                            >
                            <span class="student-count">
                                <?php echo (int) $course->total_peserta; ?>
                            </span>
                        </div>

                        <!-- Rating -->
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <span class="rating-value">
                                <?php echo number_format($course->rating_rata2, 1); ?>
                            </span>
                        </div>

                    </div>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="text-center mt-5">
                    <a href="<?php echo base_url('landing/courses'); ?>" 
                        class="btn btn-primary">Lihat Semua Kursus</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Apa Kata Mereka?</h2>
                    <p class="text-muted">Testimoni dari siswa yang telah belajar bersama kami</p>
                </div>
            </div>
            
            <div class="row g-3">
                <?php foreach($testimonials as $testimonial): ?>
                <div class="col-md-3">
                    <div class="testimonial-card p-4 rounded-3 shadow-sm h-100">
                        <div class="d-flex items-start mb-3">
                            <div class="testimonial-avatar me-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <?php echo strtoupper(substr($testimonial->nama_siswa, 0, 1)); ?>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1"><?php echo $testimonial->nama_siswa; ?></h6>
                                <p class="text-muted small mb-0">Siswa</p>
                            </div>
                            <div class="ms-auto">
                                <span class="text-warning">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= $testimonial->rating): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </span>
                            </div>
                        </div>
                        <p class="testimonial-text mb-0">"<?php echo $testimonial->review; ?>"</p>
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-book me-1"></i> <?php echo $testimonial->judul_kursus; ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">Siap Memulai Perjalanan Belajar Anda?</h2>
                    <p class="mb-0">Daftar sekarang dan dapatkan akses ke semua kursus berkualitas dari instruktur profesional</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?php echo base_url('landing/register'); ?>" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
