<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_role(['Staff', 'Administrator']);

$flash = get_flash();
$registrations = $pdo->query(
    'SELECT wr.registration_id, wr.registered_at, wr.registration_status,
            w.workshop_title, w.workshop_date, u.full_name, u.email
     FROM workshop_registrations AS wr
     INNER JOIN workshops AS w ON w.workshop_id = wr.workshop_id
     INNER JOIN users AS u ON u.user_id = wr.customer_id
     ORDER BY w.workshop_date DESC, u.full_name'
)->fetchAll();
$statuses = ['Registered', 'Attended', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Registrations | EcoSprout</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<main class="container mt-40">
    <p><a href="workshops.php">&larr; Manage workshops</a></p>
    <h1>Workshop Registrations</h1>
    <?php if ($flash !== null): ?>
        <div class="<?= escape($flash['type']) ?>"><?= escape($flash['message']) ?></div>
    <?php endif; ?>
    <?php if ($registrations === []): ?>
        <section class="card"><p>No registrations were found.</p></section>
    <?php endif; ?>
    <?php foreach ($registrations as $registration): ?>
        <article class="card mb-20">
            <h2><?= escape($registration['workshop_title']) ?></h2>
            <p><?= escape($registration['full_name']) ?> — <?= escape($registration['email']) ?></p>
            <p>Workshop date: <?= escape($registration['workshop_date']) ?></p>
            <form action="../actions/update-workshop-registration.php" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="registration_id" value="<?= (int) $registration['registration_id'] ?>">
                <select name="registration_status" class="form-control">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status ?>" <?= $registration['registration_status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Save status</button>
            </form>
        </article>
    <?php endforeach; ?>
</main>
</body>
</html>
