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
    <title>About EcoSprout | Kegalle</title>
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
            <button class="mobile-menu-btn" aria-expanded="false" aria-label="Open navigation menu">&#9776;</button>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="plants.php">Plants</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="workshops.php">Workshops</a></li>
                    <li><a href="about.php" class="active">About</a></li>
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
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
                <a href="plants.php#plant-search" class="search-button" aria-label="Search plants"
                    title="Search plants">
                    <span class="search-icon" aria-hidden="true"></span>
                </a>
                <a href="cart.php" class="cart">Cart <span
                        class="cart-badge"><?= array_sum($_SESSION['cart'] ?? []) ?></span></a>
            </div>
        </div>
    </header>

    <main>
        <section class="about-hero">
            <div class="container about-hero-content">
                <p class="eyebrow">Rooted in Kegalle</p>
                <h1>A greener home starts with the right plant.</h1>
                <p>EcoSprout brings dependable nursery plants, practical care advice and hands-on gardening services
                    together in one welcoming place.</p>
            </div>
        </section>

        <section class="container page-section">
            <div class="split-story">
                <div>
                    <p class="eyebrow">Our approach</p>
                    <h2>Plants chosen for real Sri Lankan homes.</h2>
                    <p class="text-secondary">We help customers choose plants that fit their light, space, routine and
                        goals. From a first indoor plant to a complete garden refresh, our team makes growing feel clear
                        and achievable.</p>
                    <p class="text-secondary">Every visit should leave you with a healthier plant and a little more
                        confidence caring for it.</p>
                </div>
                <div class="story-panel">
                    <span class="story-number">01</span>
                    <h3>Choose with confidence</h3>
                    <p>Browse plants by category, ask our team for guidance and order for delivery around Kegalle.</p>
                </div>
            </div>
        </section>

        <section class="band-section">
            <div class="container">
                <p class="eyebrow">Why EcoSprout</p>
                <div class="grid-3">
                    <article class="feature-tile"><span class="feature-index">01</span>
                        <h3>Local knowledge</h3>
                        <p>Advice shaped by the light, weather and growing conditions around Kegalle.</p>
                    </article>
                    <article class="feature-tile"><span class="feature-index">02</span>
                        <h3>Practical care</h3>
                        <p>Simple instructions and services that work for busy homes, offices and gardens.</p>
                    </article>
                    <article class="feature-tile"><span class="feature-index">03</span>
                        <h3>Growing together</h3>
                        <p>Workshops and support that help every customer keep learning after purchase.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="container page-section about-cta">
            <div>
                <p class="eyebrow">Ready to begin?</p>
                <h2>Find the next plant for your space.</h2>
            </div>
            <div class="flex gap-10">
                <a href="plants.php" class="btn btn-primary">Browse plants</a>
                <a href="contact.php" class="btn btn-outline">Talk to our team</a>
            </div>
        </section>
    </main>

    <footer class="public-footer">
        <div class="container footer-container">
            <div class="footer-brand"><a href="index.php" class="logo"><img src="logo.jpeg" alt="EcoSprout logo"
                        class="logo-image"> EcoSprout</a>
                <p>Plants, practical care and greener spaces for Kegalle.</p><a href="contact.php"
                    class="footer-cta">Start a conversation &#8594;</a>
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