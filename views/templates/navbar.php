<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <h2>KursusOnline</h2>
        </div>
        <div class="navbar-menu">
            <ul>
                <li class="active"><a href="<?php echo base_url('siswa/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('siswa/kursus'); ?>">Kursus</a></li>
                <li><a href="<?php echo base_url('sertifikat'); ?>">Sertifikat</a></li>
            </ul>
        </div>
        <div class="navbar-profile">
            <div class="profile-info">
                <img src="<?php echo base_url('assets/images/') . ($siswa->foto_profil ? $siswa->foto_profil : 'default.png'); ?>" alt="Profile">
                <span><?php echo $siswa->nama; ?></span>
                <div class="dropdown">
                    <ul>
                        <li><a href="<?php echo base_url('siswa/profil_siswa'); ?>">Profil</a></li>
                        <li><a href="<?php echo base_url('login/logout'); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>