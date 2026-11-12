<div class="container py-5 memory-page">
    <!-- Tombol kembali -->
    <a href="<?= site_url('memories') ?>" class="back-link d-inline-block mb-4">
        <i class="bi bi-arrow-left me-2"></i>
        Kembali ke Memory Journal
    </a>

    <article class="memory-detail-card">
        <!-- Header detail -->
        <div class="memory-detail-header">
            <p class="eyebrow">OUR LITTLE UNIVERSE</p>

            <div class="memory-detail-icon">
                <i class="bi bi-heart-fill"></i>
            </div>

            <h1><?= html_escape($memory->title) ?></h1>

            <div class="memory-detail-meta">
                <span>
                    <i class="bi bi-calendar-heart me-2"></i>
                    <?= html_escape(
                        date('d F Y', strtotime($memory->memory_date))
                    ) ?>
                </span>

                <span>
                    <i class="bi bi-person-heart me-2"></i>
                    <?= html_escape($memory->creator_name) ?>
                </span>
            </div>
        </div>

        <hr>

        <!-- Isi cerita -->
        <div class="memory-detail-story">
            <?= nl2br(html_escape($memory->story)) ?>
        </div>

        <!-- Footer -->
        <div class="memory-detail-footer">
            <p>
                <i class="bi bi-heart-fill me-2"></i>
                Sebuah kenangan kecil yang akan selalu berarti.
            </p>

            <a href="<?= site_url('memories') ?>" class="btn btn-memory">
                <i class="bi bi-journal-heart me-2"></i>
                Kembali ke Daftar Kenangan
            </a>
        </div>
    </article>
</div>