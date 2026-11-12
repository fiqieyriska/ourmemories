
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Undang Pasangan | Our Memories</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">
    <link href="<?= base_url('assets/css/auth.css') ?>"
          rel="stylesheet">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <a href="<?= site_url('dashboard') ?>" class="brand">
            <i class="bi bi-heart-fill"></i> Our Memories
        </a>

        <p class="eyebrow">OUR LITTLE UNIVERSE</p>
        <h1>Invite your person</h1>
        <p class="subtitle">
            Undang pasanganmu untuk berbagi ruang kenangan.
        </p>

        <div class="alert alert-light border">
            <i class="bi bi-heart me-2"></i>
            Ruang kenangan: <strong><?= html_escape($space->name) ?></strong>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= html_escape($error) ?>
            </div>
        <?php endif; ?>

        <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

        <?= form_open('invitations/create') ?>
            <div class="mb-3">
                <label for="email" class="form-label">
                    Email pasangan
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    value="<?= html_escape(set_value('email')) ?>"
                    maxlength="190"
                    required
                >
            </div>

            <button type="submit" class="btn btn-memory w-100">
                Buat Undangan
                <i class="bi bi-arrow-right ms-2"></i>
            </button>
        <?= form_close() ?>

        <?php if (!empty($invitation_url)): ?>
            <hr class="my-4">

            <div class="alert alert-success">
                <strong>Undangan berhasil dibuat!</strong>
                <p class="mb-2 mt-2">
                    Tautan berlaku selama 7 hari. Bagikan tautan ini hanya
                    kepada pasangan yang kamu undang.
                </p>

                <input
                    type="text"
                    id="invitationUrl"
                    class="form-control mb-3"
                    value="<?= html_escape($invitation_url) ?>"
                    readonly
                >

                <button
                    type="button"
                    class="btn btn-outline-success w-100"
                    onclick="copyInvitation()"
                >
                    <i class="bi bi-clipboard me-2"></i>
                    Salin Tautan
                </button>
            </div>

            <script>
                function copyInvitation() {
                    const field = document.getElementById('invitationUrl');
                    field.select();
                    field.setSelectionRange(0, 99999);

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(field.value)
                            .then(() => alert('Tautan berhasil disalin.'))
                            .catch(() => alert('Silakan salin tautan secara manual.'));
                    } else {
                        const copied = document.execCommand('copy');
                        alert(copied
                            ? 'Tautan berhasil disalin.'
                            : 'Silakan salin tautan secara manual.');
                    }
                }
            </script>
        <?php endif; ?>

        <p class="auth-footer">
            <a href="<?= site_url('dashboard') ?>">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </p>
    </div>
</div>
</body>
</html>
