<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();

$serviceQuery = $pdo->query(
    'SELECT
        service_id,
        service_name,
        description,
        base_price,
        duration_minutes
     FROM services
     WHERE service_status = \'Available\'
     ORDER BY service_name'
);

$services = $serviceQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gardening Services | EcoSprout</title>

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

            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="plants.php">Plants</a></li>
                    <li>
                        <a href="services.php" class="active">
                            Services
                        </a>
                    </li>
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
        <h1>Gardening Services</h1>

        <p class="text-secondary">
            Book professional gardening assistance in Kegalle.
        </p>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($services === []): ?>
            <section class="card">
                <p>No services are currently available.</p>
            </section>
        <?php else: ?>
            <section class="grid-4">
                <?php foreach ($services as $service): ?>
                    <article class="card">
                        <h2>
                            <?= escape($service['service_name']) ?>
                        </h2>

                        <p>
                            <?= nl2br(
                                escape($service['description'] ?? '')
                            ) ?>
                        </p>

                        <?php if (
                            $service['duration_minutes'] !== null
                        ): ?>
                            <p>
                                Duration:
                                <?= (int) $service['duration_minutes'] ?>
                                minutes
                            </p>
                        <?php endif; ?>

                        <strong class="text-green">
                            From LKR
                            <?= number_format(
                                (float) $service['base_price'],
                                2
                            ) ?>
                        </strong>

                        <?php if (is_logged_in()): ?>
                            <form action="actions/book-service.php" method="post" class="mt-20">
                                <?= csrf_field() ?>

                                <input type="hidden" name="service_id" value="<?= (int) $service['service_id'] ?>">

                                <label for="date-<?= (int) $service['service_id'] ?>">
                                    Booking date
                                </label>

                                <input id="date-<?= (int) $service['service_id'] ?>" type="date" name="booking_date"
                                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>

                                <label for="time-<?= (int) $service['service_id'] ?>">
                                    Booking time
                                </label>

                                <input id="time-<?= (int) $service['service_id'] ?>" type="time" name="booking_time" required>

                                <label for="address-<?= (int) $service['service_id'] ?>">
                                    Service address
                                </label>

                                <textarea id="address-<?= (int) $service['service_id'] ?>" name="service_address" maxlength="255"
                                    required></textarea>

                                <label for="notes-<?= (int) $service['service_id'] ?>">
                                    Additional notes
                                </label>

                                <textarea id="notes-<?= (int) $service['service_id'] ?>" name="customer_notes"
                                    maxlength="1000"></textarea>

                                <button type="submit" class="btn btn-primary">
                                    Book service
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="mt-20">
                                <a href="login.php" class="btn btn-primary">
                                    Log in to book
                                </a>
                            </p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>

    <script src="assets/js/main.js"></script>
</body>

</html>