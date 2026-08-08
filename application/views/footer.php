<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/landing.css">
    
</head>
<body>
 <!-- Footer -->
    <footer class="footer py-5" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-3">WorkyWay</h5>
                    <p>Platform pembelajaran online terkemuka dengan kursus berkualitas tinggi dari instruktur ahli.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5 class="mb-3">Kursus</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Programming</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Marketing</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Design</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Data Science</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5 class="mb-3">Perusahaan</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo base_url('landing/about'); ?>" class="text-white-50 text-decoration-none">Tentang Kami</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Karir</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Blog</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Bantuan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Kontak</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Yogyakarta, Indonesia</p>
                    <p><i class="fas fa-phone me-2"></i> +62 812 3456 7890</p>
                    <p><i class="fas fa-envelope me-2"></i> info@workyway.com</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> WorkyWay. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    
    <!-- Custom JS -->
    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // AJAX untuk load courses
        function loadCourses(categoryId) {
            fetch('<?php echo base_url("landing/get_courses_ajax"); ?>?category=' + categoryId)
                .then(response => response.json())
                .then(data => {
                    console.log('Courses loaded:', data);
                    // Update UI dengan data kursus
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

</body>
</html>