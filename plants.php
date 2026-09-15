<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();

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
     WHERE p.plant_status = \'Active\'
     ORDER BY p.plant_name'
);

$plants = $plantQuery->fetchAll();

$cartQuantity = array_sum($_SESSION['cart'] ?? []);
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
                aria-label="Open navigation menu"
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
                        Account
                    </a>
                <?php else: ?>
                    <a href="login.php">
                        Login
                    </a>
                <?php endif; ?>

                <a href="cart.php" class="cart">
                    🛒

                    <span class="cart-badge">
                        <?= $cartQuantity ?>
                    </span>
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="container mt-40">
            <?php if ($flash !== null): ?>
                <div class="<?= escape($flash['type']) ?>">
                    <?= escape($flash['message']) ?>
                </div>
            <?php endif; ?>

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
                        <?php
                        $stockQuantity =
                            (int) $plant['stock_quantity'];

                        $isInStock = $stockQuantity > 0;
                        ?>

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
                                <?php if ($isInStock): ?>
                                    <span class="badge badge-green">
                                        In Stock
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-gold">
                                        Out of Stock
                                    </span>
                                <?php endif; ?>

                                <span class="text-secondary font-size-sm">
                                    <?= escape(
                                        $plant['category_name']
                                    ) ?>
                                </span>
                            </div>

                            <h2>
                                <?= escape($plant['plant_name']) ?>
                            </h2>

                            <?php if (
                                !empty($plant['scientific_name'])
                            ): ?>
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
                                <?= $stockQuantity ?>
                            </p>

                            <strong class="text-green">
                                LKR
                                <?= number_format(
                                    (float) $plant['price'],
                                    2
                                ) ?>
                            </strong>

                            <div class="mt-20">
                                <a
                                    class="btn btn-outline"
                                    href="plant-details.php?id=<?= (int) $plant['plant_id'] ?>"
                                >
                                    View details
                                </a>
                            </div>

                            <form
                                action="actions/add-to-cart.php"
                                method="post"
                                class="mt-20"
                            >
                                <?= csrf_field() ?>

                                <input
                                    type="hidden"
                                    name="plant_id"
                                    value="<?= (int) $plant['plant_id'] ?>"
                                >

                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="1"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    <?= !$isInStock
                                        ? 'disabled'
                                        : '' ?>
                                >
                                    Add to cart
                                </button>
                            </form>
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
                    <li>
                        <a href="index.php">Home</a>
                    </li>

                    <li>
                        <a href="plants.php">Plants</a>
                    </li>

                    <li>
                        <a href="services.html">Services</a>
                    </li>

                    <li>
                        <a href="workshops.html">Workshops</a>
                    </li>
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

            <div>
                Plants Make Life Better
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>