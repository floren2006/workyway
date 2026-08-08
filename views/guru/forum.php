 <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Forum Diskusi</h2>
    </div>

    <!-- Main Content -->
    <div class="forum-container mt-4">
        <!-- Notifications -->
        <?php if (!empty($message_success)): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= $message_success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
       
        <?php if (!empty($message_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $message_error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Left Sidebar: Course List -->
            <div class="col-md-3">
                <div class="kursus-sidebar mb-4">
                    <h5 class="fw-bold mb-3 text-primary">
                        <i class="fas fa-book-open me-2"></i>Kursus Anda
                    </h5>
                    <?php if (empty($kursus_list)): ?>
                        <div class="empty-state py-3">
                            <i class="fas fa-book fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada kursus</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($kursus_list as $kursus): ?>
                                <?php
                                    $kursus_id = $kursus['kursus_id'] ?? 0;
                                    $judul_kursus = $kursus['judul_kursus'] ?? '';
                                    $is_active = ($selected_kursus_id == $kursus_id);
                                ?>
                                <a href="<?= base_url('guru/forum/forum?kursus_id=' . $kursus_id) ?>"
                                   class="list-group-item list-group-item-action d-flex align-items-center <?= $is_active ? 'active' : '' ?>">
                                    <i class="fas fa-graduation-cap me-2 <?= $is_active ? 'text-white' : 'text-primary' ?>"></i>
                                    <span class="text-truncate"><?= htmlspecialchars($judul_kursus) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Material Filter -->
                <?php if (!empty($materi_list)): ?>
                    <div class="kursus-sidebar">
                        <h5 class="fw-bold mb-3 text-primary">
                            <i class="fas fa-filter me-2"></i>Filter Materi
                        </h5>
                        <div class="list-group">
                            <a href="?kursus_id=<?= $selected_kursus_id ?>"
                               class="list-group-item list-group-item-action d-flex align-items-center <?= (!$selected_materi_id || $selected_materi_id == 'all') ? 'active' : '' ?>">
                                <i class="fas fa-layer-group me-2"></i>
                                Semua Materi
                            </a>
                            <?php foreach ($materi_list as $materi): ?>
                                <?php
                                    $materi_id_item = $materi['materi_id'] ?? 0;
                                    $judul_materi = $materi['judul_materi'] ?? '';
                                    $is_active = ($selected_materi_id == $materi_id_item);
                                ?>
                                <a href="?kursus_id=<?= $selected_kursus_id ?>&materi_id=<?= $materi_id_item ?>"
                                   class="list-group-item list-group-item-action d-flex align-items-center <?= $is_active ? 'active' : '' ?>">
                                    <i class="fas fa-file-alt me-2"></i>
                                    <span class="text-truncate"><?= htmlspecialchars($judul_materi) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Student Count -->
                <?php if (!empty($siswa_kursus)): ?>
                    <div class="kursus-sidebar mt-4">
                        <h6 class="fw-bold mb-2 text-primary">
                            <i class="fas fa-users me-2"></i>Peserta Kursus
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill me-2">
                                <?= count($siswa_kursus) ?>
                            </span>
                            <small class="text-muted">Siswa terdaftar</small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Panel: Chat Area -->
            <div class="col-md-9">
                <div class="chat-area">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <?php if ($selected_kursus_id): ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        <?= htmlspecialchars($selected_kursus['judul_kursus'] ?? '') ?>
                                    </h5>
                                    <small class="opacity-75">
                                        <?php
                                        if ($selected_materi_id && $selected_materi_id != 'all') {
                                            $materi_nama = '';
                                            foreach($materi_list as $materi) {
                                                $materi_id_item = $materi['materi_id'] ?? 0;
                                                if($materi_id_item == $selected_materi_id) {
                                                    $materi_nama = $materi['judul_materi'] ?? '';
                                                    break;
                                                }
                                            }
                                            echo '<i class="fas fa-file-alt me-1"></i> Materi: ' . htmlspecialchars($materi_nama);
                                        } else {
                                            echo '<i class="fas fa-layer-group me-1"></i> Semua Materi';
                                        }
                                        ?>
                                    </small>
                                </div>
                                <?php if ($total_messages > 0): ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-comment me-1"></i>
                                        <?= $total_messages ?> Pesan
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <h5 class="mb-1">Pilih Kursus</h5>
                            <small class="opacity-75">Silakan pilih kursus untuk memulai diskusi</small>
                        <?php endif; ?>
                    </div>

                    <!-- Messages Container -->
                    <div class="messages-container" id="messagesContainer">
                        <?php if (empty($messages)): ?>
                            <div class="empty-state">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Diskusi</h5>
                                <p class="text-muted">Mulailah percakapan dengan siswa Anda</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $thread): ?>
                                <?php
                                    // Extract thread data
                                    $pengirim_id = $thread['pengirim_id'] ?? 0;
                                    $pengirim_nama = $thread['pengirim_nama'] ?? '';
                                    $pengirim_role = $thread['pengirim_role'] ?? '';
                                    $pesan = $thread['pesan'] ?? '';
                                    $tanggal_post = $thread['tanggal_post'] ?? '';
                                    $forum_id = $thread['forum_id'] ?? 0;
                                    $jumlah_balasan = $thread['jumlah_balasan'] ?? 0;
                                    $balasan = $thread['balasan'] ?? [];
                                   
                                    // Determine if message is from current user (guru)
                                    $is_current_user = ($pengirim_id == $current_user_id);
                                    $role_class = strtolower($pengirim_role);
                                    $initial_class = $pengirim_role == 'guru' ? 'guru-initial' : 'siswa-initial';
                                ?>
                                <div class="message-item" id="thread-<?= $forum_id ?>">
                                    <div class="d-flex align-items-start">
                                        <!-- User Initial -->
                                        <div class="user-initial <?= $initial_class ?> me-3">
                                            <?= strtoupper(substr($pengirim_nama, 0, 1)) ?>
                                        </div>
                                       
                                        <!-- Thread Content -->
                                        <div class="thread-content <?= $role_class ?> flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <strong><?= htmlspecialchars($pengirim_nama) ?></strong>
                                                    <span class="badge-role badge <?= $pengirim_role == 'guru' ? 'bg-primary' : 'bg-success' ?> ms-2">
                                                        <?= $pengirim_role == 'guru' ? 'Guru' : 'Siswa' ?>
                                                    </span>
                                                </div>
                                                <small class="message-time">
                                                    <?= date('d M Y, H:i', strtotime($tanggal_post)) ?>
                                                </small>
                                            </div>
                                            <p class="mb-2"><?= nl2br(htmlspecialchars($pesan)) ?></p>
                                           
                                            <!-- Show Replies Link -->
                                            <?php if ($jumlah_balasan > 0): ?>
                                                <a href="javascript:void(0)" class="show-replies"
                                                   onclick="toggleReplies(<?= $forum_id ?>)">
                                                    <i class="fas fa-reply me-1"></i>
                                                    <?= $jumlah_balasan ?> Balasan
                                                </a>
                                            <?php endif; ?>
                                           
                                            <!-- Reply Button -->
                                            <div class="mt-2">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
                                                   onclick="toggleReplyForm(<?= $forum_id ?>)">
                                                    <i class="fas fa-reply me-1"></i> Balas
                                                </a>
                                               
                                                <!-- Delete Button (for own messages) -->
                                                <?php if ($is_current_user): ?>
                                                    <a href="<?= base_url('guru/forum/hapus_pesan/' . $forum_id) ?>"
                                                       class="btn btn-sm btn-outline-danger ms-2"
                                                       onclick="return confirm('Hapus pesan ini?')">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                           
                                            <!-- Reply Form -->
                                            <div class="reply-form" id="reply-form-<?= $forum_id ?>">
                                                <form action="<?= base_url('guru/forum/kirim_balasan') ?>" method="POST">
                                                    <input type="hidden" name="thread_id" value="<?= $forum_id ?>">
                                                    <input type="hidden" name="materi_id" value="<?= $selected_materi_id ?>">
                                                    <div class="mb-2">
                                                        <textarea name="balasan" class="form-control" rows="2"
                                                                  placeholder="Tulis balasan..." required></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-paper-plane me-1"></i> Kirim Balasan
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-sm ms-2"
                                                            onclick="toggleReplyForm(<?= $forum_id ?>)">
                                                        Batal
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <!-- Replies Container -->
                                    <?php if (!empty($balasan)): ?>
                                        <div class="replies-container" id="replies-<?= $forum_id ?>" style="display: none;">
                                            <?php foreach ($balasan as $reply): ?>
                                                <?php
                                                    $reply_pengirim_id = $reply['pengirim_id'] ?? 0;
                                                    $reply_pengirim_nama = $reply['pengirim_nama'] ?? '';
                                                    $reply_pengirim_role = $reply['pengirim_role'] ?? '';
                                                    $reply_pesan = $reply['pesan'] ?? '';
                                                    $reply_tanggal = $reply['tanggal_post'] ?? '';
                                                    $reply_forum_id = $reply['forum_id'] ?? 0;
                                                    $reply_role_class = strtolower($reply_pengirim_role);
                                                    $reply_initial_class = $reply_pengirim_role == 'guru' ? 'guru-initial' : 'siswa-initial';
                                                    $reply_is_current_user = ($reply_pengirim_id == $current_user_id);
                                                ?>
                                                <div class="reply-item d-flex align-items-start">
                                                    <div class="user-initial <?= $reply_initial_class ?> me-3" style="width: 30px; height: 30px; font-size: 12px;">
                                                        <?= strtoupper(substr($reply_pengirim_nama, 0, 1)) ?>
                                                    </div>
                                                    <div class="reply-content <?= $reply_role_class ?> flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <div>
                                                                <strong style="font-size: 0.9rem;"><?= htmlspecialchars($reply_pengirim_nama) ?></strong>
                                                                <span class="badge-role badge <?= $reply_pengirim_role == 'guru' ? 'bg-primary' : 'bg-success' ?> ms-1" style="font-size: 0.6rem;">
                                                                    <?= $reply_pengirim_role == 'guru' ? 'Guru' : 'Siswa' ?>
                                                                </span>
                                                            </div>
                                                            <small class="message-time">
                                                                <?= date('d M Y, H:i', strtotime($reply_tanggal)) ?>
                                                            </small>
                                                        </div>
                                                        <p style="font-size: 0.9rem; margin-bottom: 0;"><?= nl2br(htmlspecialchars($reply_pesan)) ?></p>
                                                       
                                                        <!-- Delete Button for own replies -->
                                                        <?php if ($reply_is_current_user): ?>
                                                            <div class="mt-1">
                                                                <a href="<?= base_url('guru/forum/hapus_pesan/' . $reply_forum_id) ?>"
                                                                   class="btn btn-xs btn-outline-danger"
                                                                   onclick="return confirm('Hapus balasan ini?')" style="font-size: 0.7rem; padding: 2px 6px;">
                                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Send Message Form -->
                    <?php if ($selected_kursus_id): ?>
                        <div class="p-3 border-top">
                            <form action="<?= base_url('guru/forum/kirim_pesan') ?>" method="POST" id="sendMessageForm">
                                <input type="hidden" name="kursus_id" value="<?= $selected_kursus_id ?>">
                                <input type="hidden" name="materi_id" value="<?= $selected_materi_id ?>">
                               
                                <div class="input-group">
                                    <textarea name="pesan" class="form-control" rows="2"
                                              placeholder="Ketik pesan Anda di sini..."
                                              id="messageInput" required></textarea>
                                    <button type="submit" class="btn btn-primary btn-send">
                                        <i class="fas fa-paper-plane me-1"></i> Kirim
                                    </button>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pesan akan dikirim ke semua siswa yang mengikuti kursus ini
                                </small>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto scroll to bottom of chat
        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
       
        // Scroll to bottom on page load
        window.onload = function() {
            scrollToBottom();
           
            // Focus on message input
            const messageInput = document.getElementById('messageInput');
            if (messageInput) {
                messageInput.focus();
            }
        };
       
        // Form submission feedback
        const sendMessageForm = document.getElementById('sendMessageForm');
        if (sendMessageForm) {
            sendMessageForm.addEventListener('submit', function() {
                // Clear input after submit
                setTimeout(function() {
                    const messageInput = document.getElementById('messageInput');
                    if (messageInput) {
                        messageInput.value = '';
                        messageInput.focus();
                    }
                }, 100);
            });
        }
       
        // Toggle reply form
        function toggleReplyForm(threadId) {
            const replyForm = document.getElementById('reply-form-' + threadId);
            if (!replyForm) return;
           
            // Hide all other reply forms
            document.querySelectorAll('.reply-form').forEach(form => {
                if (form.id !== 'reply-form-' + threadId) {
                    form.style.display = 'none';
                }
            });
           
            // Toggle current reply form
            if (replyForm.style.display === 'block') {
                replyForm.style.display = 'none';
            } else {
                replyForm.style.display = 'block';
                const textarea = replyForm.querySelector('textarea');
                if (textarea) {
                    textarea.focus();
                }
            }
        }
       
        // Toggle replies visibility
        function toggleReplies(threadId) {
            const repliesContainer = document.getElementById('replies-' + threadId);
            const showLink = document.querySelector(`#thread-${threadId} .show-replies`);
           
            if (!repliesContainer) return;
           
            if (repliesContainer.style.display === 'none' || repliesContainer.style.display === '') {
                repliesContainer.style.display = 'block';
                if (showLink) {
                    showLink.innerHTML = '<i class="fas fa-chevron-up me-1"></i>Sembunyikan Balasan';
                }
            } else {
                repliesContainer.style.display = 'none';
                if (showLink) {
                    showLink.innerHTML = '<i class="fas fa-reply me-1"></i>' + repliesContainer.children.length + ' Balasan';
                }
            }
        }
       
        // Check for URL hash to scroll to specific thread
        window.addEventListener('hashchange', function() {
            const hash = window.location.hash;
            if (hash.startsWith('#thread-')) {
                const threadId = hash.replace('#thread-', '');
                const threadElement = document.getElementById('thread-' + threadId);
                if (threadElement) {
                    threadElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
