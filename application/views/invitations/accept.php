
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join Our Memories</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="<?= base_url('assets/css/auth.css') ?>"
          rel="stylesheet">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <a href="<?= site_url('home') ?>" class="brand">
            <i class="bi bi-heart-fill"></i> Our Memories
        </a>

        <p class="eyebrow">YOU ARE INVITED</p>
        <h1>Join our little universe</h1>
        <p class="subtitle">
            Kamu diundang untuk berbagi kenangan bersama.
        </p>

        <div class="alert alert-light border">
            <div class="mb-2">
                <strong>Ruang kenangan:</strong>
                <?= html_escape($invitation->space_name) ?>
            </div>
            <div>
                <strong>Email akun:</strong>
                <?= html_escape($invitation->invited_email) ?>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= html_escape($error) ?>
            </div>
        <?php endif; ?>

        <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

        <?= form_open(current_url()) ?>
            <div class="mb-3">
                <label for="name" class="form-label">Nama kamu</label>
                <input type="text" name="name" id="name"
                       class="form-control"
                       value="<?= html_escape(set_value('name')) ?>"
                       maxlength="100" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Buat password</label>
                <input type="password" name="password" id="password"
                       class="form-control" minlength="8" required>
            </div>

            <div class="mb-4">
                <label for="password_confirm" class="form-label">
                    Konfirmasi password
                </label>
                <input type="password" name="password_confirm"
                       id="password_confirm" class="form-control"
                       minlength="8" required>
            </div>

            <button type="submit" class="btn btn-memory w-100">
                Join Our Memories
            </button>
        <?= form_close() ?>

        <p class="auth-footer">
            Sudah punya akun?
            <a href="<?= site_url('login') ?>">Login terlebih dahulu</a>,
            lalu buka kembali tautan undangan.
        </p>
    </div>
</div>
</body>
</html>
