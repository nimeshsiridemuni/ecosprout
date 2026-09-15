<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_role(['Staff', 'Administrator']);

$flash = get_flash();
$query = $pdo->query(
    'SELECT sb.*, s.service_name, u.full_name, u.email
     FROM service_bookings AS sb
     INNER JOIN services AS s ON s.service_id = sb.service_id
     INNER JOIN users AS u ON u.user_id = sb.customer_id
     ORDER BY sb.booking_date, sb.booking_time'
);
$bookings = $query->fetchAll();
$statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Bookings | EcoSprout</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<main class="container mt-40">
    <p><a href="dashboard.php">&larr; Dashboard</a></p>
    <h1>Service Bookings</h1>
    <?php if ($flash !== null): ?>
        <div class="<?= escape($flash['type']) ?>"><?= escape($flash['message']) ?></div>
    <?php endif; ?>
    <?php if ($bookings === []): ?>
        <section class="card"><p>No service bookings were found.</p></section>
    <?php endif; ?>
    <?php foreach ($bookings as $booking): ?>
        <article class="card mb-20">
            <h2><?= escape($booking['service_name']) ?></h2>
            <p><?= escape($booking['full_name']) ?> — <?= escape($booking['email']) ?></p>
            <p><?= escape($booking['booking_date']) ?> at <?= escape($booking['booking_time']) ?></p>
            <p><?= escape($booking['service_address']) ?></p>
            <form action="../actions/update-booking-status.php" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="booking_id" value="<?= (int) $booking['booking_id'] ?>">
                <select name="booking_status" class="form-control">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status ?>" <?= $booking['booking_status'] === $status ? 'selected' : '' ?>>
                            <?= $status ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Update booking</button>
            </form>
        </article>
    <?php endforeach; ?>
</main>
</body>
</html>
