
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Our Memories</title>

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
        <a href="<?= site_url('home') ?>" class="brand">
            <i class="bi bi-heart-fill"></i> Our Memories
        </a>

        <p class="eyebrow">WELCOME BACK</p>
        <h1>Our story awaits.</h1>
        <p class="subtitle">Come back to your little universe.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= html_escape($error) ?>
            </div>
        <?php endif; ?>

        <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

        <?= form_open('login') ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" id="email"
                       class="form-control"
                       value="<?= html_escape(set_value('email')) ?>"
                       required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-memory w-100">
                Sign In <i class="bi bi-heart ms-2"></i>
            </button>
        <?= form_close() ?>

        <p class="auth-footer">
            New to Our Memories?
            <a href="<?= site_url('register') ?>">Create account</a>
        </p>
    </div>
</div>
</body>
</html>
