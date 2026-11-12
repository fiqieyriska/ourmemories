
<div class="container py-5 gallery-page">

    <!-- Header -->
    <div class="memory-heading d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="eyebrow">OUR LITTLE UNIVERSE</p>
            <h1>Our Gallery</h1>
            <p class="text-muted mb-0">
                Potongan kecil dari cerita indah kita.
            </p>
        </div>

        <button type="button"
                class="btn btn-memory"
                data-bs-toggle="modal"
                data-bs-target="#uploadPhotoModal">
            <i class="bi bi-cloud-arrow-up me-2"></i>Tambah Foto
        </button>
    </div>

    <!-- Notifikasi -->
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

    <!-- Galeri -->
    <?php if (empty($photos)): ?>

        <div class="empty-memory text-center p-5">
            <div class="empty-memory-icon mb-3">
                <i class="bi bi-images"></i>
            </div>

            <h3>Belum ada foto tersimpan</h3>

            <p class="text-muted">
                Yuk, simpan foto-foto yang menjadi bagian dari cerita kalian.
            </p>

            <button type="button"
                    class="btn btn-memory mt-2"
                    data-bs-toggle="modal"
                    data-bs-target="#uploadPhotoModal">
                <i class="bi bi-plus-lg me-2"></i>Unggah Foto Pertama
            </button>
        </div>

    <?php else: ?>

        <div class="gallery-summary mb-4">
            <span>
                <i class="bi bi-images me-2"></i>
                <?= count($photos) ?> foto kenangan
            </span>
        </div>

        <div class="row g-4">

            <?php foreach ($photos as $photo): ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                    <article class="gallery-card h-100">

                        <!-- Foto -->
                        <button type="button"
                                class="gallery-photo-button"
                                data-bs-toggle="modal"
                                data-bs-target="#photoDetailModal"
                                data-photo-src="<?= base_url('assets/uploads/gallery/' . rawurlencode($photo->photo_filename)) ?>"
                                data-photo-title="<?= html_escape($photo->title) ?>"
                                data-photo-caption="<?= html_escape($photo->caption ?? '') ?>"
                                data-photo-date="<?= html_escape(date('d F Y', strtotime($photo->photo_date))) ?>"
                                aria-label="Lihat <?= html_escape($photo->title) ?>">

                            <img
                                src="<?= base_url('assets/uploads/gallery/' . rawurlencode($photo->photo_filename)) ?>"
                                alt="<?= html_escape($photo->title) ?>"
                                class="gallery-photo"
                                loading="lazy">

                            <span class="gallery-photo-overlay">
                                <i class="bi bi-zoom-in"></i>
                            </span>
                        </button>

                        <!-- Informasi foto -->
                        <div class="gallery-card-body">

                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h3 class="gallery-photo-title">
                                    <?= html_escape($photo->title) ?>
                                </h3>

                                <!-- Tombol hapus -->
                                <button type="button"
                                        class="gallery-delete-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deletePhotoModal"
                                        data-photo-id="<?= (int) $photo->id ?>"
                                        data-photo-title="<?= html_escape($photo->title) ?>"
                                        aria-label="Hapus foto">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>

                            <p class="gallery-photo-date">
                                <i class="bi bi-calendar-heart me-2"></i>
                                <?= html_escape(date('d F Y', strtotime($photo->photo_date))) ?>
                            </p>

                            <?php if (!empty($photo->caption)): ?>
                                <p class="gallery-photo-caption">
                                    <?= nl2br(html_escape($photo->caption)) ?>
                                </p>
                            <?php endif; ?>

                            <div class="gallery-card-footer">
                                <i class="bi bi-heart-fill me-1"></i>
                                <?= html_escape($photo->uploader_name ?? 'Our Memories') ?>
                            </div>

                        </div>
                    </article>

                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <!-- Modal Upload Foto -->
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1"
         aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content gallery-modal">

                <form action="<?= site_url('gallery/create') ?>"
                      method="post"
                      enctype="multipart/form-data">

                    <?php if ($this->config->item('csrf_protection')): ?>
                        <input type="hidden"
                               name="<?= $this->security->get_csrf_token_name() ?>"
                               value="<?= $this->security->get_csrf_hash() ?>">
                    <?php endif; ?>

                    <div class="modal-header">
                        <div>
                            <p class="eyebrow mb-2">SAVE A LITTLE MOMENT</p>
                            <h5 class="modal-title" id="uploadPhotoModalLabel">
                                Tambahkan Foto
                            </h5>
                        </div>

                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body p-4">

                        <div class="gallery-upload-preview mb-4"
                             id="galleryUploadPreview">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p class="mb-1">Pilih foto kenanganmu</p>
                            <small>JPG, PNG, atau WEBP · Maksimal 5 MB</small>
                        </div>

                        <div class="mb-3">
                            <label for="galleryPhotoFile" class="form-label">
                                Foto <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                   class="form-control"
                                   id="galleryPhotoFile"
                                   name="photo"
                                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                   required>

                            <div class="form-text">
                                Gunakan foto yang berukuran maksimal 5 MB.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="galleryPhotoTitle" class="form-label">
                                Judul Foto <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="galleryPhotoTitle"
                                   name="title"
                                   maxlength="150"
                                   placeholder="Contoh: Our First Date"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="galleryPhotoDate" class="form-label">
                                Tanggal Kenangan <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   class="form-control"
                                   id="galleryPhotoDate"
                                   name="photo_date"
                                   required>
                        </div>

                        <div class="mb-0">
                            <label for="galleryPhotoCaption" class="form-label">
                                Caption
                            </label>

                            <textarea class="form-control"
                                      id="galleryPhotoCaption"
                                      name="caption"
                                      rows="3"
                                      maxlength="5000"
                                      placeholder="Ceritakan momen di balik foto ini..."></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-memory">
                            <i class="bi bi-heart me-2"></i>Simpan Foto
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- Modal Detail Foto -->
    <div class="modal fade" id="photoDetailModal" tabindex="-1"
         aria-labelledby="photoDetailModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content gallery-detail-modal">

                <div class="modal-header border-0">
                    <h5 class="modal-title" id="photoDetailModalLabel">
                        Little Moments
                    </h5>

                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                </div>

                <div class="modal-body pt-0">
                    <img src="" alt="" id="detailPhotoImage"
                         class="gallery-detail-image">

                    <div class="p-3">
                        <p class="gallery-photo-date" id="detailPhotoDate"></p>
                        <p class="gallery-detail-caption mb-0"
                           id="detailPhotoCaption"></p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deletePhotoModal" tabindex="-1"
         aria-labelledby="deletePhotoModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content gallery-modal">

                <div class="modal-body text-center p-4 p-md-5">

                    <div class="delete-modal-icon mb-3">
                        <i class="bi bi-trash3"></i>
                    </div>

                    <h5 class="mb-2" id="deletePhotoModalLabel">
                        Hapus Foto?
                    </h5>

                    <p class="text-muted mb-2">
                        Yakin ingin menghapus foto
                        <strong id="deletePhotoTitle"></strong>?
                    </p>

                    <p class="small text-muted mb-4">
                        Foto yang dihapus tidak dapat dikembalikan.
                    </p>

                    <form id="deletePhotoForm" method="post" action="">

                        <?php if ($this->config->item('csrf_protection')): ?>
                            <input type="hidden"
                                   name="<?= $this->security->get_csrf_token_name() ?>"
                                   value="<?= $this->security->get_csrf_hash() ?>">
                        <?php endif; ?>

                        <div class="d-flex justify-content-center gap-2">

                            <button type="button"
                                    class="btn btn-light rounded-pill px-4"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit"
                                    class="btn btn-delete-confirm rounded-pill px-4">
                                <i class="bi bi-trash3 me-2"></i>Ya, Hapus
                            </button>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
