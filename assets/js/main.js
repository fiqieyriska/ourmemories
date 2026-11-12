document.addEventListener('DOMContentLoaded', function () {
    // Smooth scrolling untuk navigasi anchor.
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            const selector = this.getAttribute('href');

            if (!selector || selector === '#') {
                return;
            }

            const target = document.querySelector(selector);

            if (target) {
                event.preventDefault();

                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // Tutup menu mobile setelah tautan dipilih.
                const navbar = document.querySelector('#mainNavbar');
                const collapse = bootstrap.Collapse.getInstance(navbar);

                if (collapse && navbar.classList.contains('show')) {
                    collapse.hide();
                }
            }
        });
    });

    console.log('Our Memories siap digunakan.');
    
});

document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteMemoryModal');
    const confirmButton = document.getElementById('confirmDeleteMemory');
    const titleElement = document.getElementById('deleteMemoryTitle');

    if (!deleteModal || !confirmButton) return;

    let selectedDeleteForm = null;

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        if (!button) return;

        selectedDeleteForm = button.closest('form');

        const title = button.getAttribute('data-memory-title') || '';

        if (titleElement) {
            titleElement.textContent = title ? '"' + title + '"' : '';
        }
    });

    confirmButton.addEventListener('click', function () {
        if (!selectedDeleteForm) {
            console.error('Form hapus kenangan tidak ditemukan.');
            return;
        }

        confirmButton.disabled = true;
        confirmButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...';

        selectedDeleteForm.submit();
    });

    deleteModal.addEventListener('hidden.bs.modal', function () {
        selectedDeleteForm = null;
        confirmButton.disabled = false;
        confirmButton.innerHTML =
            '<i class="bi bi-trash3 me-2"></i>Ya, Hapus';
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // Preview foto sebelum diunggah
    const photoInput = document.getElementById('galleryPhotoFile');
    const preview = document.getElementById('galleryUploadPreview');

    if (photoInput && preview) {
        const defaultPreview = preview.innerHTML;

        photoInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                preview.innerHTML = defaultPreview;
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Pilih foto JPG, PNG, atau WEBP.');
                this.value = '';
                preview.innerHTML = defaultPreview;
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran foto maksimal 5 MB.');
                this.value = '';
                preview.innerHTML = defaultPreview;
                return;
            }

            const image = document.createElement('img');
            const objectUrl = URL.createObjectURL(file);

            image.src = objectUrl;
            image.alt = 'Preview foto yang akan diunggah';

            preview.replaceChildren(image);

            image.addEventListener('load', function () {
                URL.revokeObjectURL(objectUrl);
            });
        });
    }

    // Detail foto
    const detailModal = document.getElementById('photoDetailModal');

    if (detailModal) {
        detailModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            if (!button) return;

            const image = document.getElementById('detailPhotoImage');

            image.src = button.getAttribute('data-photo-src') || '';
            image.alt = button.getAttribute('data-photo-title') || '';

            document.getElementById('photoDetailModalLabel').textContent =
                button.getAttribute('data-photo-title') || 'Little Moments';

            document.getElementById('detailPhotoDate').textContent =
                button.getAttribute('data-photo-date') || '';

            document.getElementById('detailPhotoCaption').textContent =
                button.getAttribute('data-photo-caption') || '';
        });

        detailModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('detailPhotoImage').src = '';
        });
    }

    // Konfirmasi hapus foto
    const deletePhotoModal = document.getElementById('deletePhotoModal');
    const deletePhotoForm = document.getElementById('deletePhotoForm');

    if (deletePhotoModal && deletePhotoForm) {
        deletePhotoModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            if (!button) return;

            const photoId = button.getAttribute('data-photo-id');
            const photoTitle = button.getAttribute('data-photo-title') || '';

            deletePhotoForm.action =
                'index.php/gallery/delete/' + encodeURIComponent(photoId);

            document.getElementById('deletePhotoTitle').textContent =
                '"' + photoTitle + '"';
        });
    }

    function previewHomePhoto(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];

        if (!file.type.startsWith('image/')) {
            alert('Pilih file gambar yang valid.');
            input.value = '';
            return;
        }

        const form = input.closest('form');
        const polaroid = form.closest('.polaroid');
        const image = polaroid.querySelector('.polaroid-image');

        image.src = URL.createObjectURL(file);
    }

});


