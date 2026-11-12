
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="<?= base_url('assets/css/auth.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="auth-card mx-auto">
            <p class="eyebrow">OUR LITTLE UNIVERSE</p>
            <h1>Welcome, <?= html_escape($user_name) ?>.</h1>
            <p class="subtitle">
                Your shared story starts here.
            </p>

            <div class="alert alert-light border">
                Ruang kenangan kamu sudah siap. Fitur memoar akan kita
                bangun pada tahap berikutnya.
            </div>

            <?= form_open('logout') ?>
                <button type="submit" class="btn btn-memory w-100">
                    Sign Out
                </button>
            <?= form_close() ?>
        </div>
    </div>
</body>
</html>
