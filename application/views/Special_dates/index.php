
<div class="container py-5 special-dates-page">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <span class="special-eyebrow">OUR LITTLE MILESTONES</span>
            <h1 class="special-heading mt-2 mb-2">Special Dates</h1>
            <p class="text-muted mb-0">
                Some dates are more than numbers. They are pieces of our story.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-memory"
            data-bs-toggle="modal"
            data-bs-target="#addDateModal">
            <i class="bi bi-calendar-heart me-2"></i>
            Tambah Tanggal
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

    <?php
    $upcoming = null;

    foreach ($special_dates as $event) {
        if ($event->days_remaining >= 0) {
            $upcoming = $event;
            break;
        }
    }
    ?>

    <!-- Highlight acara terdekat -->
    <?php if ($upcoming): ?>
        <div class="upcoming-date-banner mb-5">
            <div class="upcoming-date-decoration">
                <i class="bi bi-heart-fill"></i>
            </div>

            <div class="upcoming-date-content">
                <span class="special-eyebrow">OUR NEXT SPECIAL MOMENT</span>

                <h2>
                    <?= html_escape($upcoming->title); ?>
                </h2>

                <p class="mb-0">
                    <?= date('d F Y', strtotime($upcoming->next_event_date)); ?>
                </p>
            </div>

            <div class="countdown-number">
                <strong><?= (int) $upcoming->days_remaining; ?></strong>
                <span>
                    <?= $upcoming->days_remaining === 1 ? 'DAY TO GO' : 'DAYS TO GO'; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Daftar tanggal -->
    <div class="special-section-heading mb-3">
        <h2>Our important dates</h2>
        <span>
            <?= count($special_dates); ?>
            <?= count($special_dates) === 1 ? 'moment' : 'moments'; ?>
        </span>
    </div>

    <?php if (!empty($special_dates)): ?>

        <div class="row g-4">

            <?php foreach ($special_dates as $event): ?>
                <div class="col-md-6 col-xl-4">
                    <article class="special-date-card h-100">

                        <div class="special-card-top">
                            <span class="special-category">
                                <i class="bi bi-heart me-1"></i>
                                <?= html_escape($event->category); ?>
                            </span>

                            <div class="special-card-actions">
                                <button
                                    type="button"
                                    class="special-action-btn"
                                    title="Edit tanggal"
                                    aria-label="Edit <?= html_escape($event->title); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDateModal"
                                    data-id="<?= (int) $event->id; ?>"
                                    data-title="<?= html_escape($event->title); ?>"
                                    data-date="<?= html_escape($event->event_date); ?>"
                                    data-category="<?= html_escape($event->category); ?>"
                                    data-description="<?= html_escape($event->description ?? ''); ?>"
                                    data-recurring="<?= (int) $event->is_recurring; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button
                                    type="button"
                                    class="special-action-btn special-delete-btn"
                                    title="Hapus tanggal"
                                    aria-label="Hapus <?= html_escape($event->title); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteDateModal"
                                    data-id="<?= (int) $event->id; ?>"
                                    data-title="<?= html_escape($event->title); ?>">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>

                        <div class="special-date-calendar">
                            <span>
                                <?= date('M', strtotime($event->next_event_date)); ?>
                            </span>

                            <strong>
                                <?= date('d', strtotime($event->next_event_date)); ?>
                            </strong>

                            <small>
                                <?= date('Y', strtotime($event->next_event_date)); ?>
                            </small>
                        </div>

                        <h3 class="special-date-title">
                            <?= html_escape($event->title); ?>
                        </h3>

                        <?php if (!empty($event->description)): ?>
                            <p class="special-date-description">
                                <?= nl2br(html_escape($event->description)); ?>
                            </p>
                        <?php else: ?>
                            <p class="special-date-description text-muted">
                                A little moment worth remembering.
                            </p>
                        <?php endif; ?>

                        <div class="special-date-footer">
                            <span>
                                <i class="bi bi-arrow-repeat me-1"></i>
                                <?= (int) $event->is_recurring === 1
                                    ? 'Every year'
                                    : 'One-time event'; ?>
                            </span>

                            <strong>
                                <?php if ($event->days_remaining === 0): ?>
                                    Today!
                                <?php elseif ($event->days_remaining > 0): ?>
                                    <?= (int) $event->days_remaining; ?> days left
                                <?php else: ?>
                                    Past event
                                <?php endif; ?>
                            </strong>
                        </div>

                    </article>
                </div>
            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="special-empty-state text-center">
            <div class="special-empty-icon">
                <i class="bi bi-calendar-heart"></i>
            </div>

            <h2>Belum ada tanggal spesial</h2>

            <p>
                Simpan hari jadian, ulang tahun, first date, dan momen
                penting lainnya di sini.
            </p>

            <button
                type="button"
                class="btn btn-memory"
                data-bs-toggle="modal"
                data-bs-target="#addDateModal">
                Tambah Tanggal Pertama
            </button>
        </div>

    <?php endif; ?>

</div>

<!-- MODAL TAMBAH TANGGAL -->
<div class="modal fade" id="addDateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content special-modal">

            <form action="<?= site_url('special-dates/create'); ?>" method="post">

                <?php if ($this->config->item('csrf_protection')): ?>
                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>">
                <?php endif; ?>

                <div class="modal-header">
                    <div>
                        <span class="special-eyebrow">SAVE THIS MOMENT</span>
                        <h2 class="modal-title fs-4 mt-1">Tambah tanggal spesial</h2>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="addDateTitle" class="form-label">Nama acara</label>
                        <input
                            type="text"
                            class="form-control"
                            id="addDateTitle"
                            name="title"
                            maxlength="150"
                            placeholder="Contoh: Anniversary pertama"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="addEventDate" class="form-label">Tanggal</label>
                        <input
                            type="date"
                            class="form-control"
                            id="addEventDate"
                            name="event_date"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="addDateCategory" class="form-label">Kategori</label>
                        <select
                            class="form-select"
                            id="addDateCategory"
                            name="category"
                            required>
                            <option value="Anniversary">Anniversary</option>
                            <option value="Birthday">Birthday</option>
                            <option value="First Date">First Date</option>
                            <option value="Other" selected>Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="addDateDescription" class="form-label">
                            Catatan (opsional)
                        </label>
                        <textarea
                            class="form-control"
                            id="addDateDescription"
                            name="description"
                            rows="3"
                            maxlength="5000"
                            placeholder="Kenapa tanggal ini begitu berarti?"></textarea>
                    </div>

                    <div class="form-check">
                        <input
                            type="hidden"
                            name="is_recurring"
                            value="0">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_recurring"
                            id="addDateRecurring"
                            value="1"
                            checked>

                        <label class="form-check-label" for="addDateRecurring">
                            Ulangi setiap tahun
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-memory">
                        <i class="bi bi-heart me-1"></i>
                        Simpan Tanggal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- MODAL EDIT TANGGAL -->
<div class="modal fade" id="editDateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content special-modal">

            <form id="editDateForm" action="" method="post">

                <?php if ($this->config->item('csrf_protection')): ?>
                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>">
                <?php endif; ?>

                <div class="modal-header">
                    <div>
                        <span class="special-eyebrow">UPDATE YOUR MEMORY</span>
                        <h2 class="modal-title fs-4 mt-1">Edit tanggal</h2>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="editDateTitle" class="form-label">Nama acara</label>
                        <input
                            type="text"
                            class="form-control"
                            id="editDateTitle"
                            name="title"
                            maxlength="150"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editEventDate" class="form-label">Tanggal</label>
                        <input
                            type="date"
                            class="form-control"
                            id="editEventDate"
                            name="event_date"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editDateCategory" class="form-label">Kategori</label>
                        <select
                            class="form-select"
                            id="editDateCategory"
                            name="category"
                            required>
                            <option value="Anniversary">Anniversary</option>
                            <option value="Birthday">Birthday</option>
                            <option value="First Date">First Date</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editDateDescription" class="form-label">Catatan</label>
                        <textarea
                            class="form-control"
                            id="editDateDescription"
                            name="description"
                            rows="3"
                            maxlength="5000"></textarea>
                    </div>

                    <div class="form-check">
                        <input
                            type="hidden"
                            name="is_recurring"
                            value="0">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_recurring"
                            id="editDateRecurring"
                            value="1">

                        <label class="form-check-label" for="editDateRecurring">
                            Ulangi setiap tahun
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-memory">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- MODAL HAPUS TANGGAL -->
<div class="modal fade" id="deleteDateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content special-modal">

            <div class="modal-header">
                <h2 class="modal-title fs-5">Hapus tanggal spesial?</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Apakah kamu yakin ingin menghapus
                <strong id="deleteDateTitle"></strong>?
                Tindakan ini tidak dapat dibatalkan.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </button>

                <form id="deleteDateForm" action="" method="post">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editDateModal');
    const deleteModal = document.getElementById('deleteDateModal');

    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            document.getElementById('editDateForm').action =
                <?= json_encode(rtrim(site_url('special-dates/edit'), '/')); ?>
                + '/' + encodeURIComponent(button.dataset.id);

            document.getElementById('editDateTitle').value =
                button.dataset.title || '';

            document.getElementById('editEventDate').value =
                button.dataset.date || '';

            document.getElementById('editDateCategory').value =
                button.dataset.category || 'Other';

            document.getElementById('editDateDescription').value =
                button.dataset.description || '';

            document.getElementById('editDateRecurring').checked =
                button.dataset.recurring === '1';
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            document.getElementById('deleteDateForm').action =
                <?= json_encode(rtrim(site_url('special-dates/delete'), '/')); ?>
                + '/' + encodeURIComponent(button.dataset.id);

            document.getElementById('deleteDateTitle').textContent =
                button.dataset.title || 'tanggal ini';
        });
    }
});
</script>
