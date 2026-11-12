
<div class="container py-4 py-lg-5 love-letters-page">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="text-uppercase small mb-2 love-eyebrow">
                A little piece of my heart
            </p>
            <h1 class="love-heading mb-2">Love Letters</h1>
            <p class="text-muted mb-0">
                Kumpulan kata yang menyimpan cerita kita.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-love"
            data-bs-toggle="modal"
            data-bs-target="#writeLetterModal">
            <i class="bi bi-envelope-heart me-2"></i>
            Tulis Surat
        </button>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('success')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($letters)): ?>

        <div class="row g-4">

            <?php foreach ($letters as $letter): ?>
                <div class="col-md-6 col-xl-4">
                    <article class="love-letter-card h-100">

                        <div class="love-letter-card-top">
                            <i class="bi bi-envelope-paper-heart"></i>
                            <span>
                                <?= html_escape(
                                    !empty($letter->published_at)
                                        ? date('d M Y', strtotime($letter->published_at))
                                        : date('d M Y', strtotime($letter->created_at))
                                ); ?>
                            </span>
                        </div>

                        <h2 class="love-card-title">
                            <?= html_escape($letter->title); ?>
                        </h2>

                        <p class="love-card-excerpt">
                            <?= html_escape(
                                mb_substr(strip_tags($letter->content), 0, 130)
                                . (mb_strlen(strip_tags($letter->content)) > 130 ? '...' : '')
                            ); ?>
                        </p>

                        <div class="love-letter-card-footer">
                            <span class="love-author">
                                <i class="bi bi-heart-fill me-1"></i>
                                <?= html_escape($letter->author_name ?: 'Someone special'); ?>
                            </span>

                            <a
                                href="<?= site_url('love-letters/detail/' . $letter->id); ?>"
                                class="love-read-link">
                                Baca surat <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                    </article>
                </div>
            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="love-empty-state text-center">
            <div class="love-empty-icon">
                <i class="bi bi-envelope-heart"></i>
            </div>

            <h2>Belum ada surat cinta</h2>

            <p>
                Mulai simpan kata-kata yang ingin kamu kenang bersama pasangan.
            </p>

            <button
                type="button"
                class="btn btn-love"
                data-bs-toggle="modal"
                data-bs-target="#writeLetterModal">
                Tulis Surat Pertama
            </button>
        </div>

    <?php endif; ?>

</div>

<!-- Modal Tulis Surat -->
<div
    class="modal fade"
    id="writeLetterModal"
    tabindex="-1"
    aria-labelledby="writeLetterModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content love-modal-content">

            <form
                action="<?= site_url('love-letters/create'); ?>"
                method="post">

                <?php if ($this->config->item('csrf_protection')): ?>
                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>">
                <?php endif; ?>

                <div class="modal-header">
                    <div>
                        <p class="love-eyebrow small mb-1">Dear, my favorite person</p>
                        <h2 class="modal-title fs-4" id="writeLetterModalLabel">
                            Tulis surat cinta
                        </h2>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="letterTitle" class="form-label">
                            Judul surat
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="letterTitle"
                            name="title"
                            maxlength="150"
                            placeholder="Contoh: Untuk rumah ternyamanku"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="letterContent" class="form-label">
                            Isi surat
                        </label>

                        <textarea
                            class="form-control love-letter-textarea"
                            id="letterContent"
                            name="content"
                            rows="9"
                            placeholder="Tuliskan semua hal yang ingin kamu sampaikan..."
                            required></textarea>

                        <div class="form-text">
                            Tulis dengan jujur. Surat ini akan menjadi bagian dari kenangan kalian.
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-love">
                        <i class="bi bi-envelope-heart me-2"></i>
                        Simpan Surat
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
