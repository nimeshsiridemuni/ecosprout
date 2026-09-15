<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$plantQuery = $pdo->query(
    'SELECT
        p.plant_id,
        p.plant_name,
        p.scientific_name,
        p.price,
        p.stock_quantity,
        p.plant_status,
        c.category_name
     FROM plants AS p
     INNER JOIN categories AS c
        ON c.category_id = p.category_id
     WHERE p.plant_status = "Active"
     ORDER BY p.plant_name'
);

$plants = $plantQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Plant Catalogue | EcoSprout</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="assets/css/components.css"
    >

    <link
        rel="stylesheet"
        href="assets/css/responsive.css"
    >
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
                aria-label="Menu"
            >
                ☰
            </button>

            <nav class="main-nav">
                <ul>
                    <li>
                        <a href="index.php">Home</a>
                    </li>

                    <li>
                        <a href="plants.php" class="active">
                            Plants
                        </a>
                    </li>

                    <li>
                        <a href="services.html">Services</a>
                    </li>

                    <li>
                        <a href="workshops.html">Workshops</a>
                    </li>

                    <li>
                        <a href="about.html">About</a>
                    </li>

                    <li>
                        <a href="contact.html">Contact</a>
                    </li>
                </ul>
            </nav>

            <div class="header-icons">
                <?php if (is_logged_in()): ?>
                    <a href="customer/dashboard.php">
                        👤
                    </a>
                <?php else: ?>
                    <a href="login.php">
                        Login
                    </a>
                <?php endif; ?>

                <span class="cart">
                    🛒
                    <span class="cart-badge">0</span>
                </span>
            </div>
        </div>
    </header>

    <main>
        <div class="container mt-40">
            <div class="flex justify-between align-center mb-20">
                <div>
                    <h1>Plant Catalogue</h1>

                    <p class="text-secondary">
                        Browse plants available from our nursery
                        in Kegalle.
                    </p>
                </div>

                <p class="text-secondary">
                    <?= count($plants) ?> plants available
                </p>
            </div>

            <?php if ($plants === []): ?>
                <div class="card text-center">
                    <h2>No plants are currently available</h2>

                    <p class="text-secondary">
                        Please check the catalogue again later.
                    </p>
                </div>
            <?php else: ?>
                <section class="grid-4">
                    <?php foreach ($plants as $plant): ?>
                        <article class="card card-interactive">
                            <div
                                style="
                                    height: 200px;
                                    background: #ffffff;
                                    border-radius: 8px;
                                    margin-bottom: 15px;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    color: #122118;
                                "
                            >
                                Plant Image
                            </div>

                            <div
                                class="flex justify-between align-center mb-10"
                            >
                                <span class="badge badge-green">
                                    In Stock
                                </span>

                                <span class="text-secondary font-size-sm">
                                    <?= escape($plant['category_name']) ?>
                                </span>
                            </div>

                            <h2>
                                <?= escape($plant['plant_name']) ?>
                            </h2>

                            <?php if ($plant['scientific_name'] !== null): ?>
                                <p
                                    class="text-secondary font-size-sm"
                                    style="font-style: italic;"
                                >
                                    <?= escape(
                                        $plant['scientific_name']
                                    ) ?>
                                </p>
                            <?php endif; ?>

                            <p>
                                Stock:
                                <?= (int) $plant['stock_quantity'] ?>
                            </p>

                            <div
                                class="flex justify-between align-center mt-20"
                            >
                                <strong class="text-green">
                                    LKR
                                    <?= number_format(
                                        (float) $plant['price'],
                                        2
                                    ) ?>
                                </strong>

                                <a
                                    class="btn btn-primary"
                                    href="plant-details.php?id=<?= (int) $plant['plant_id'] ?>"
                                >
                                    View
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <footer class="public-footer">
        <div class="container footer-container">
            <div class="footer-col">
                <a href="index.php" class="logo">
                    <span class="logo-icon">🌿</span>
                    EcoSprout
                </a>

                <p>
                    Your trusted nursery for plants and
                    professional gardening services in Kegalle.
                </p>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>

                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="plants.php">Plants</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="workshops.html">Workshops</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact Us</h4>

                <ul class="text-secondary">
                    <li>Kegalle, Sri Lanka</li>
                    <li>info@ecosprout.lk</li>
                </ul>
            </div>
        </div>

        <div class="container footer-bottom">
            <div>
                &copy; 2026 EcoSprout. All rights reserved.
            </div>

            <div>Plants Make Life Better</div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>