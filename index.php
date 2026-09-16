<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EcoSprout Nursery | Kegalle</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <header class="public-header">
        <div class="container header-container">
            <a href="index.php" class="logo">
                <img src="logo.jpeg" alt="EcoSprout logo" class="logo-image">
                EcoSprout
            </a>

            <button class="mobile-menu-btn" aria-expanded="false" aria-label="Open navigation menu">
                ☰
            </button>

            <nav class="main-nav">
                <ul>
                    <li>
                        <a href="index.php" class="active">Home</a>
                    </li>

                    <li><a href="plants.php">Plants</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="workshops.php">Workshops</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>

                    <?php if (($_SESSION['role'] ?? '') === 'Customer'): ?>
                        <li><a href="customer/orders.php">My Orders</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="header-icons">
                <?php if (is_logged_in()): ?>
                    <a href="<?= dashboard_path() ?>">
                        <?= escape($_SESSION['email'] ?? 'Account') ?>
                    </a>

                    <form action="logout.php" method="post">
                        <?= csrf_field() ?>

                        <button type="submit" class="btn btn-outline">
                            Logout
                        </button>
                    </form>
                <?php else: ?>
                    <a href="login.php">Login</a>

                    <a href="register.php" class="btn btn-primary">
                        Register
                    </a>
                <?php endif; ?>

                <a href="plants.php#plant-search" class="search-button" aria-label="Search plants"
                    title="Search plants">
                    <span class="search-icon" aria-hidden="true"></span>
                </a>

                <a href="cart.php">
                    🛒
                    <?= array_sum($_SESSION['cart'] ?? []) ?>
                </a>
            </div>
        </div>
    </header>

    <main>
        <?php if ($flash !== null): ?>
            <div class="container mt-20">
                <div class="<?= escape($flash['type']) ?>">
                    <?= escape($flash['message']) ?>
                </div>
            </div>
        <?php endif; ?>

        <section class="home-hero">
            <div class="container hero-content">
                <p class="eyebrow">EcoSprout Nursery · Kegalle</p>
                <h1>Bring a little more life home.</h1>
                <p class="hero-copy">Plants for brighter rooms, healthier gardens and slower weekends. Find the right
                    fit, then grow with support from our local nursery team.</p>
                <div class="flex gap-10 hero-actions">
                    <a href="plants.php" class="btn btn-primary">Explore the nursery</a>
                    <a href="contact.php" class="btn btn-light">Ask a plant question</a>
                </div>
                <div class="hero-meta"><span>10+ plant varieties</span><span>Local Kegalle delivery</span><span>Care
                        advice included</span></div>
            </div>
        </section>

        <section class="container page-section intro-section">
            <div>
                <p class="eyebrow">A better way to grow</p>
                <h2>Good plants. Clear advice. No guesswork.</h2>
            </div>
            <p class="section-lead">Choose from plants we know will thrive in your space, book practical help when you
                need it, and learn how to keep every new leaf looking its best.</p>
        </section>

        <section class="container home-feature-grid">
            <a href="plants.php" class="feature-card feature-card-large">
                <img src="plant1.png" alt="Plants in the EcoSprout catalogue" class="feature-card-image">
                <div class="feature-card-content">
                    <span class="feature-index">01 / Catalogue</span>
                    <h3>Find your next green companion.</h3>
                    <p>Indoor, outdoor, flowering, herbal and low-maintenance favourites.</p>
                    <span class="feature-arrow">Explore plants &#8594;</span>
                </div>
            </a>
            <a href="services.php" class="feature-card">
                <img src="plant2.png" alt="Plant care and gardening services" class="feature-card-image">
                <div class="feature-card-content">
                    <span class="feature-index">02 / Services</span>
                    <h3>Make your garden feel intentional.</h3>
                    <p>From repotting to full garden styling.</p>
                    <span class="feature-arrow">View services &#8594;</span>
                </div>
            </a>
            <a href="workshops.php" class="feature-card feature-card-accent">
                <img src="plant3.png" alt="Plants for EcoSprout workshops" class="feature-card-image">
                <div class="feature-card-content">
                    <span class="feature-index">03 / Workshops</span>
                    <h3>Learn what your plants are saying.</h3>
                    <p>Practical sessions for confident care.</p>
                    <span class="feature-arrow">See workshops &#8594;</span>
                </div>
            </a>
        </section>

        <section class="container page-section home-quote">
            <p class="quote-mark">&#8220;</p>
            <blockquote>Every plant has a place where it can flourish. We help you find it.</blockquote>
            <a href="about.php" class="text-link">More about EcoSprout &#8594;</a>
        </section>
    </main>

    <footer class="public-footer">
        <div class="container footer-container">
            <div class="footer-brand">
                <a href="index.php" class="logo"><img src="logo.jpeg" alt="EcoSprout logo" class="logo-image">
                    EcoSprout</a>
                <p>Plants, practical care and greener spaces for Kegalle.</p>
                <a href="contact.php" class="footer-cta">Start a conversation &#8594;</a>
            </div>
            <div class="footer-col">
                <h4>Explore</h4>
                <ul>
                    <li><a href="plants.php">Plant catalogue</a></li>
                    <li><a href="services.php">Gardening services</a></li>
                    <li><a href="workshops.php">Workshops</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>EcoSprout</h4>
                <ul>
                    <li><a href="about.php">Our story</a></li>
                    <li><a href="contact.php">Contact team</a></li>
                    <li><a href="login.php">Customer login</a></li>
                </ul>
            </div>
            <div class="footer-col footer-contact">
                <h4>Visit</h4>
                <p>Kegalle, Sri Lanka</p><a href="mailto:info@ecosprout.lk">info@ecosprout.lk</a>
                <p>+94 35 123 4567</p>
            </div>
        </div>
        <div class="container footer-bottom"><span>&copy; 2026 EcoSprout Nursery</span><span>Grow well. Live
                green.</span></div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>

</html>