
<div class="container py-5 memory-page">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <a href="<?= site_url('memories') ?>" class="back-link">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Memory Journal
            </a>

            <div class="memory-form-card mt-4">
                <p class="eyebrow">A NEW CHAPTER</p>
                <h1>Tulis Kenangan</h1>
                <p class="text-muted mb-4">
                    Abadikan momen kecil yang berarti bagi kalian.
                </p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?= html_escape($error) ?>
                    </div>
                <?php endif; ?>

                <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

                <?= form_open('memories/create') ?>

                    <div class="mb-4">
                        <label for="title" class="form-label">
                            Judul Kenangan
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control"
                            placeholder="Contoh: Hari pertama kita bertemu"
                            maxlength="150"
                            value="<?= html_escape(set_value('title')) ?>"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="memory_date" class="form-label">
                            Tanggal Kenangan
                        </label>
                        <input
                            type="date"
                            name="memory_date"
                            id="memory_date"
                            class="form-control"
                            value="<?= html_escape(set_value('memory_date', date('Y-m-d'))) ?>"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="story" class="form-label">
                            Cerita Kenangan
                        </label>
                        <textarea
                            name="story"
                            id="story"
                            class="form-control"
                            rows="8"
                            placeholder="Ceritakan momen yang ingin kalian simpan..."
                            required
                        ><?= html_escape(set_value('story')) ?></textarea>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-memory">
                            <i class="bi bi-heart-fill me-2"></i>
                            Simpan Kenangan
                        </button>

                        <a href="<?= site_url('memories') ?>"
                           class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>

        </div>
    </div>
</div>
