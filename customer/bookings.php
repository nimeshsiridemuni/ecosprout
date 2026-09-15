<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

$flash = get_flash();

$bookingQuery = $pdo->prepare(
    'SELECT
        sb.booking_id,
        sb.booking_date,
        sb.booking_time,
        sb.service_address,
        sb.booking_status,
        s.service_name,
        s.base_price
     FROM service_bookings AS sb
     INNER JOIN services AS s
        ON s.service_id = sb.service_id
     WHERE sb.customer_id = :customer_id
     ORDER BY sb.booking_date DESC,
              sb.booking_time DESC'
);

$bookingQuery->execute([
    'customer_id' => (int) $_SESSION['user_id'],
]);

$bookings = $bookingQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Bookings | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="../services.php">
                &larr; View services
            </a>
        </p>

        <h1>My Service Bookings</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($bookings === []): ?>
            <section class="card">
                <p>You have not booked a service yet.</p>
            </section>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <article class="card mb-20">
                    <h2>
                        <?= escape($booking['service_name']) ?>
                    </h2>

                    <p>
                        Booking reference:
                        #<?= (int) $booking['booking_id'] ?>
                    </p>

                    <p>
                        Date:
                        <?= escape($booking['booking_date']) ?>
                    </p>

                    <p>
                        Time:
                        <?= escape($booking['booking_time']) ?>
                    </p>

                    <p>
                        Address:
                        <?= escape($booking['service_address']) ?>
                    </p>

                    <p>
                        Starting price:
                        LKR
                        <?= number_format(
                            (float) $booking['base_price'],
                            2
                        ) ?>
                    </p>

                    <p>
                        Status:
                        <strong>
                            <?= escape($booking['booking_status']) ?>
                        </strong>
                    </p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>