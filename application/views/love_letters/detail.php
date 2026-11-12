
<div class="container py-4 py-lg-5 love-letters-page">

    <div class="mb-4">
        <a href="<?= site_url('love-letters'); ?>" class="love-back-link">
            <i class="bi bi-arrow-left me-2"></i>
            Kembali ke Love Letters
        </a>
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

    <article class="love-letter-paper">

        <div class="love-paper-decoration">
            <i class="bi bi-heart-fill"></i>
        </div>

        <div class="text-center mb-4">
            <p class="love-eyebrow small mb-2">A letter from the heart</p>

            <h1 class="love-heading love-detail-heading">
                <?= html_escape($letter->title); ?>
            </h1>

            <div class="love-detail-meta">
                <span>
                    <i class="bi bi-person-heart me-1"></i>
                    <?= html_escape($letter->author_name ?: 'Someone special'); ?>
                </span>

                <span class="mx-2">·</span>

                <span>
                    <?= html_escape(
                        !empty($letter->published_at)
                            ? date('d F Y', strtotime($letter->published_at))
                            : date('d F Y', strtotime($letter->created_at))
                    ); ?>
                </span>
            </div>
        </div>

        <div class="love-letter-content"><?= html_escape($letter->content); ?></div>

        <div class="love-paper-signature">
            <p>With love, always.</p>
            <i class="bi bi-heart-fill"></i>
        </div>

    </article>

    <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">

        <button
            type="button"
            class="btn btn-outline-secondary"
            data-bs-toggle="modal"
            data-bs-target="#editLetterModal">
            <i class="bi bi-pencil me-1"></i>
            Edit Surat
        </button>

        <button
            type="button"
            class="btn btn-outline-danger"
            data-bs-toggle="modal"
            data-bs-target="#deleteLetterModal">
            <i class="bi bi-trash me-1"></i>
            Hapus Surat
        </button>

    </div>

</div>

<!-- Modal Edit Surat -->
<div
    class="modal fade"
    id="editLetterModal"
    tabindex="-1"
    aria-labelledby="editLetterModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content love-modal-content">

            <form
                action="<?= site_url('love-letters/edit/' . $letter->id); ?>"
                method="post">

                <?php if ($this->config->item('csrf_protection')): ?>
                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>">
                <?php endif; ?>

                <div class="modal-header">
                    <h2 class="modal-title fs-4" id="editLetterModalLabel">
                        Edit Surat
                    </h2>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="editLetterTitle" class="form-label">
                            Judul surat
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="editLetterTitle"
                            name="title"
                            maxlength="150"
                            value="<?= html_escape($letter->title); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editLetterContent" class="form-label">
                            Isi surat
                        </label>

                        <textarea
                            class="form-control love-letter-textarea"
                            id="editLetterContent"
                            name="content"
                            rows="9"
                            required><?= html_escape($letter->content); ?></textarea>
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
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div
    class="modal fade"
    id="deleteLetterModal"
    tabindex="-1"
    aria-labelledby="deleteLetterModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content love-modal-content">

            <div class="modal-header">
                <h2 class="modal-title fs-5" id="deleteLetterModalLabel">
                    Hapus surat ini?
                </h2>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                Surat yang dihapus tidak dapat dikembalikan. Yakin ingin melanjutkan?
            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <form
                    action="<?= site_url('love-letters/delete/' . $letter->id); ?>"
                    method="post">

                    <?php if ($this->config->item('csrf_protection')): ?>
                        <input
                            type="hidden"
                            name="<?= $this->security->get_csrf_token_name(); ?>"
                            value="<?= $this->security->get_csrf_hash(); ?>">
                    <?php endif; ?>

                    <button type="submit" class="btn btn-danger">
                        Ya, Hapus
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>
