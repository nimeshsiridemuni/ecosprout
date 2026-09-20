<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();

$customerName = $_SESSION['full_name'] ?? '';
$customerEmail = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us | EcoSprout</title>

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
                &#9776;
            </button>

            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="plants.php">Plants</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="workshops.php">Workshops</a></li>
                    <li><a href="about.php">About</a></li>
                    <li>
                        <a href="contact.php" class="active">
                            Contact
                        </a>
                    </li>

                    <?php if (($_SESSION['role'] ?? '') === 'Customer'): ?>
                        <li><a href="customer/orders.php">My Orders</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="header-icons">
                <a href="plants.php#plant-search" class="search-button" aria-label="Search plants"
                    title="Search plants">
                    <span class="search-icon" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </header>

    <main class="container page-section contact-layout">
        <section class="contact-intro">
            <p class="eyebrow">Let&apos;s grow something good</p>
            <h1>Tell us what your garden needs.</h1>
            <p class="text-secondary">Ask about a plant, delivery, a gardening service or an upcoming workshop. A member
                of our Kegalle team will get back to you.</p>

            <div class="contact-details">
                <div><span class="detail-label">Visit</span><strong>EcoSprout Nursery</strong>
                    <p>Kegalle, Sri Lanka</p>
                </div>
                <div><span class="detail-label">Write</span><strong>info@ecosprout.lk</strong>
                    <p>We reply during nursery hours.</p>
                </div>
                <div><span class="detail-label">Call</span><strong>+94 35 123 4567</strong>
                    <p>Mon - Sat, 8:30 AM - 5:30 PM</p>
                </div>
            </div>
        </section>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <section class="card contact-form-card">
            <form action="actions/submit-inquiry.php" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="customer_name">
                        Your name
                    </label>

                    <input id="customer_name" name="customer_name" type="text" class="form-control"
                        value="<?= escape($customerName) ?>" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label for="customer_email">
                        Email address
                    </label>

                    <input id="customer_email" name="customer_email" type="email" class="form-control" maxlength="150"
                        value="<?= escape((string) $customerEmail) ?>" required>
                </div>

                <div class="form-group">
                    <label for="subject">
                        Subject
                    </label>

                    <input id="subject" name="subject" type="text" class="form-control" maxlength="150" required>
                </div>

                <div class="form-group">
                    <label for="message">
                        Message
                    </label>

                    <textarea id="message" name="message" class="form-control" maxlength="2000" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit inquiry
                </button>
            </form>
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
