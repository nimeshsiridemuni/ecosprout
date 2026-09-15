<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>EcoSprout Nursery | Kegalle</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <header class="public-header">
        <div class="container header-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">🌿</span>
                EcoSprout
            </a>

            <button
                class="mobile-menu-btn"
                aria-expanded="false"
                aria-label="Open navigation menu"
            >
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
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>

            <div class="header-icons">
                <?php if (is_logged_in()): ?>
                    <a href="<?= dashboard_path() ?>">Account</a>

                    <form action="logout.php" method="post">
                        <?= csrf_field() ?>

                        <button
                            type="submit"
                            class="btn btn-outline"
                        >
                            Logout
                        </button>
                    </form>
                <?php else: ?>
                    <a href="login.php">Login</a>

                    <a href="register.php" class="btn btn-primary">
                        Register
                    </a>
                <?php endif; ?>

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

        <section class="container mt-40">
            <div class="card">
                <p class="text-green">
                    EcoSprout Nursery — Kegalle
                </p>

                <h1>
                    Grow healthier plants with local nursery
                    support
                </h1>

                <p class="text-secondary">
                    Shop nursery plants, book gardening services,
                    join practical workshops and ask our team for
                    plant-care advice.
                </p>

                <div class="flex gap-10 mt-20">
                    <a href="plants.php" class="btn btn-primary">
                        Browse plants
                    </a>

                    <a href="services.php" class="btn btn-outline">
                        View services
                    </a>
                </div>
            </div>
        </section>

        <section class="container mt-40">
            <h2>What EcoSprout provides</h2>

            <div class="grid-4">
                <article class="card">
                    <h3>Nursery plants</h3>
                    <p>
                        Browse indoor, outdoor, herbal and
                        flowering plants.
                    </p>
                </article>

                <article class="card">
                    <h3>Gardening services</h3>
                    <p>
                        Book professional assistance for your
                        home or business.
                    </p>
                </article>

                <article class="card">
                    <h3>Workshops</h3>
                    <p>
                        Learn practical plant-care techniques
                        from experienced staff.
                    </p>
                </article>

                <article class="card">
                    <h3>Plant-care support</h3>
                    <p>
                        Send an inquiry and receive help from
                        the nursery team.
                    </p>
                </article>
            </div>
        </section>
    </main>

    <footer class="public-footer mt-40">
        <div class="container footer-container">
            <div>
                <h3>EcoSprout</h3>
                <p>Kegalle, Sri Lanka</p>
            </div>

            <div>
                <p>info@ecosprout.lk</p>
                <p>&copy; 2026 EcoSprout</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
