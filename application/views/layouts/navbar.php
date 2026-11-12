
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="<?= site_url('home') ?>">
            <i class="bi bi-heart-fill me-2"></i>Our Memories
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                <?php if ($this->session->userdata('user_id')): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('home') ?>">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('memories') ?>">
                            Memories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('gallery') ?>">
                            Our Gallery
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('invitations/create') ?>">
                            Invite Partner
                        </a>
                    </li>

                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= html_escape($this->session->userdata('user_name')) ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <?= form_open('logout', array('class' => 'm-0')) ?>
                            <button type="submit" class="btn btn-memory">
                                Sign Out
                                <i class="bi bi-box-arrow-right ms-1"></i>
                            </button>
                        <?= form_close() ?>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#memories">Memories</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#about">Our Story</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('login') ?>">
                            Sign In
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('register') ?>" class="btn btn-memory">
                            Get Started
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>
                    