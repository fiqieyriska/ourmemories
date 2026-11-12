
<main>
    <!-- HERO SECTION -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <span class="eyebrow">
                        <i class="bi bi-stars me-2"></i>
                        OUR LITTLE UNIVERSE
                    </span>

                    <h1 class="hero-title">
                        Every moment,
                        <span>a memory.</span>
                    </h1>

                    <p class="hero-description">
                        A little place to keep our stories,
                        our laughter, and all the little things
                        that make our love special.
                    </p>

                    <a href="#memories" class="btn btn-memory">
                        Explore Our Story
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <p class="hero-note mt-4">
                        <i class="bi bi-heart-fill me-2"></i>
                        Made for us, remembered forever.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="scrapbook-board">

                        <div class="tape"></div>

                        <div class="polaroid polaroid-one">
                            <div class="photo-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                            <p>our favorite day</p>
                        </div>

                        <div class="polaroid polaroid-two">
                            <div class="photo-placeholder photo-pink">
                                <i class="bi bi-heart"></i>
                            </div>
                            <p>little moments</p>
                        </div>

                        <div class="floating-heart heart-one">♥</div>
                        <div class="floating-heart heart-two">♡</div>

                        <div class="date-note">
                            <i class="bi bi-calendar-heart"></i>
                            <span>Our story, one page at a time.</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- MEMORIES FEATURES -->
    <section class="memories-section" id="memories">
        <div class="container">

            <div class="section-heading text-center">
                <span class="eyebrow">A COLLECTION OF US</span>
                <h2>Little things, <em>big memories.</em></h2>
                <p>Every little moment deserves its own page.</p>
            </div>

            <div class="row g-4 mt-3">

                <!-- MEMORY JOURNAL -->
                <div class="col-md-6 col-lg-3">
                    <a
                        href="<?= site_url('memories'); ?>"
                        class="memory-card-link"
                        aria-label="Buka Memory Journal">

                        <div class="memory-card">
                            <div class="memory-icon">
                                <i class="bi bi-journal-heart"></i>
                            </div>

                            <h3>Memory Journal</h3>

                            <p>
                                Write down the moments we never want to forget.
                            </p>

                            <span class="coming-soon">
                                Our stories
                                <i class="bi bi-arrow-up-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- OUR GALLERY -->
                <div class="col-md-6 col-lg-3">
                    <a
                        href="<?= site_url('gallery'); ?>"
                        class="memory-card-link"
                        aria-label="Buka Our Gallery">

                        <div class="memory-card">
                            <div class="memory-icon">
                                <i class="bi bi-images"></i>
                            </div>

                            <h3>Our Gallery</h3>

                            <p>
                                A little album filled with our favorite pictures.
                            </p>

                            <span class="coming-soon">
                                Our photos
                                <i class="bi bi-arrow-up-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- LOVE LETTERS -->
                <div class="col-md-6 col-lg-3">
                    <a
                        href="<?= site_url('love-letters'); ?>"
                        class="memory-card-link"
                        aria-label="Buka Love Letters">

                        <div class="memory-card">
                            <div class="memory-icon">
                                <i class="bi bi-envelope-heart"></i>
                            </div>

                            <h3>Love Letters</h3>

                            <p>
                                Words from the heart, written just for you.
                            </p>

                            <span class="coming-soon">
                                Our letters
                                <i class="bi bi-arrow-up-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- SPECIAL DATES -->
                <div class="col-md-6 col-lg-3">
                    <a
                        href="<?= site_url('special-dates'); ?>"
                        class="memory-card-link"
                        aria-label="Buka Special Dates">

                        <div class="memory-card">
                            <div class="memory-icon">
                                <i class="bi bi-calendar-heart"></i>
                            </div>

                            <h3>Special Dates</h3>

                            <p>
                                Dates that will always mean something to us.
                            </p>

                            <span class="coming-soon">
                                Our milestones
                                <i class="bi bi-arrow-up-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- QUOTE SECTION -->
    <section class="quote-section" id="about">
        <div class="container text-center">
            <i class="bi bi-quote quote-icon"></i>

            <h2>
                Someday, these little moments
                will become our favorite stories.
            </h2>

            <p>And this is where we keep them.</p>

            <span class="quote-heart">♥</span>
        </div>
    </section>
</main>
