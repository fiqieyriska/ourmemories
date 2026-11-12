
<div class="container py-5 memory-page">
    <div class="memory-heading d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="eyebrow">OUR LITTLE UNIVERSE</p>
            <h1>Memory Journal</h1>
            <p class="text-muted mb-0">
                Setiap cerita adalah bagian dari perjalanan kita.
            </p>
        </div>

        <a href="<?= site_url('memories/create') ?>" class="btn btn-memory">
            <i class="bi bi-plus-lg me-2"></i>Tulis Kenangan
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= html_escape($this->session->flashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= html_escape($this->session->flashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($memories)): ?>
        <div class="empty-memory text-center p-5">
            <div class="empty-memory-icon mb-3">
                <i class="bi bi-journal-heart"></i>
            </div>

            <h3>Belum ada cerita tersimpan</h3>
            <p class="text-muted">
                Mulai abadikan momen kecil yang berarti bagi kalian.
            </p>

            <a href="<?= site_url('memories/create') ?>" class="btn btn-memory mt-2">
                Buat Kenangan Pertama
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($memories as $memory): ?>
                <div class="col-12 col-md-6 col-lg-4">
            <article class="memory-card h-100">

                <!-- Header kartu: ikon hati dan tombol hapus -->
                <div class="memory-card-top">
                    <div class="memory-card-decoration">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <form action="<?= site_url('memories/delete/' . $memory->id) ?>"
                        method="post"
                        class="memory-delete-form m-0">

                        <?php if ($this->config->item('csrf_protection')): ?>
                            <input type="hidden"
                                name="<?= $this->security->get_csrf_token_name() ?>"
                                value="<?= $this->security->get_csrf_hash() ?>">
                        <?php endif; ?>

                        <button type="button"
                                class="memory-delete-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteMemoryModal"
                                data-memory-id="<?= (int) $memory->id ?>"
                                data-memory-title="<?= html_escape($memory->title) ?>"
                                title="Hapus kenangan"
                                aria-label="Hapus kenangan">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>

                <!-- Tanggal kenangan -->
                <p class="memory-date">
                    <i class="bi bi-calendar-heart me-2"></i>
                    <?= html_escape(date('d F Y', strtotime($memory->memory_date))) ?>
                </p>

                <!-- Judul -->
                <h3><?= html_escape($memory->title) ?></h3>

                <!-- Ringkasan cerita -->
                <p class="memory-story">
                    <?= nl2br(html_escape(
                        mb_strimwidth($memory->story, 0, 180, '...')
                    )) ?>
                </p>

                <!-- Pembuat kenangan -->
                <div class="memory-card-footer">
                    <span>
                        <i class="bi bi-person-heart me-1"></i>
                        <?= html_escape($memory->creator_name) ?>
                    </span>
                </div>

                <!-- Tautan detail -->
                <a href="<?= site_url('memories/detail/' . $memory->id) ?>"
                class="back-link d-inline-block mt-3">
                    Baca cerita lengkap
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </article>
                </div>

            <?php endforeach; ?>
        </div>

        <!-- Modal Konfirmasi Hapus: cukup satu kali -->
        <div class="modal fade" id="deleteMemoryModal" tabindex="-1"
             aria-labelledby="deleteMemoryModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content memory-delete-modal">
                    <div class="modal-body text-center p-4 p-md-5">

                        <div class="delete-modal-icon mb-3">
                            <i class="bi bi-trash3"></i>
                        </div>

                        <h5 class="mb-2" id="deleteMemoryModalLabel">
                            Hapus Kenangan?
                        </h5>

                        <p class="text-muted mb-2">
                            Apakah kamu yakin ingin menghapus kenangan
                            <strong id="deleteMemoryTitle"></strong>?
                        </p>

                        <p class="small text-muted mb-4">
                            Kenangan yang dihapus tidak dapat dikembalikan.
                        </p>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="button"
                                    class="btn btn-light rounded-pill px-4"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="button"
                                    class="btn btn-delete-confirm rounded-pill px-4"
                                    id="confirmDeleteMemory">
                                <i class="bi bi-trash3 me-2"></i>Ya, Hapus
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
