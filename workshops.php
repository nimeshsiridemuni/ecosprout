<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();

$workshopQuery = $pdo->query(
    'SELECT
        w.workshop_id,
        w.workshop_title,
        w.description,
        w.workshop_date,
        w.start_time,
        w.location,
        w.capacity,
        w.registration_fee,
        COUNT(wr.registration_id) AS registered_count
     FROM workshops AS w
     LEFT JOIN workshop_registrations AS wr
        ON wr.workshop_id = w.workshop_id
       AND wr.registration_status = \'Registered\'
     WHERE w.workshop_status = \'Scheduled\'
       AND w.workshop_date >= CURRENT_DATE
     GROUP BY
        w.workshop_id,
        w.workshop_title,
        w.description,
        w.workshop_date,
        w.start_time,
        w.location,
        w.capacity,
        w.registration_fee
     ORDER BY w.workshop_date, w.start_time'
);

$workshops = $workshopQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Workshops | EcoSprout</title>

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
                    <li>
                        <a href="workshops.php" class="active">
                            Workshops
                        </a>
                    </li>

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
                        Account
                    </a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>

                <a href="plants.php#plant-search" class="search-button" aria-label="Search plants"
                    title="Search plants">
                    <span class="search-icon" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mt-40">
        <h1>Plant-care Workshops</h1>

        <p class="text-secondary">
            Join practical workshops organised by EcoSprout.
        </p>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($workshops === []): ?>
            <section class="card">
                <p>No upcoming workshops are available.</p>
            </section>
        <?php else: ?>
            <section class="grid-4">
                <?php foreach ($workshops as $workshop): ?>
                    <?php
                    $spacesRemaining =
                        (int) $workshop['capacity']
                        - (int) $workshop['registered_count'];

                    $isFull = $spacesRemaining <= 0;
                    ?>

                    <article class="card">
                        <h2>
                            <?= escape(
                                $workshop['workshop_title']
                            ) ?>
                        </h2>

                        <p>
                            <?= nl2br(
                                escape($workshop['description'] ?? '')
                            ) ?>
                        </p>

                        <p>
                            <strong>Date:</strong>
                            <?= escape($workshop['workshop_date']) ?>
                        </p>

                        <p>
                            <strong>Time:</strong>
                            <?= escape($workshop['start_time']) ?>
                        </p>

                        <p>
                            <strong>Location:</strong>
                            <?= escape($workshop['location']) ?>
                        </p>

                        <p>
                            <strong>Fee:</strong>
                            LKR
                            <?= number_format(
                                (float) $workshop['registration_fee'],
                                2
                            ) ?>
                        </p>

                        <p>
                            <strong>Spaces remaining:</strong>
                            <?= max(0, $spacesRemaining) ?>
                        </p>

                        <?php if ($isFull): ?>
                            <span class="badge badge-gold">
                                Workshop full
                            </span>
                        <?php elseif (($_SESSION['role'] ?? '') === 'Customer'): ?>
                            <form action="actions/register-workshop.php" method="post">
                                <?= csrf_field() ?>

                                <input type="hidden" name="workshop_id" value="<?= (int) $workshop['workshop_id'] ?>">

                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </form>
                        <?php elseif (!is_logged_in()): ?>
                            <a href="login.php" class="btn btn-primary">
                                Log in to register
                            </a>
                        <?php else: ?>
                            <span class="text-secondary">
                                Registration requires a customer account.
                            </span>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>

    <script src="assets/js/main.js"></script>
</body>

</html>
