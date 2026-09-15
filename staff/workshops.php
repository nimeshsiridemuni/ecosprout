<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_role(['Staff', 'Administrator']);

$flash = get_flash();
$workshops = $pdo->query(
    'SELECT w.*, COUNT(wr.registration_id) AS registration_count
     FROM workshops AS w
     LEFT JOIN workshop_registrations AS wr
       ON wr.workshop_id = w.workshop_id
      AND wr.registration_status = \'Registered\'
     GROUP BY w.workshop_id
     ORDER BY w.workshop_date DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Workshops | EcoSprout</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<main class="container mt-40">
    <p><a href="dashboard.php">&larr; Dashboard</a></p>
    <h1>Manage Workshops</h1>
    <p>
        <a href="workshop-registrations.php" class="btn btn-outline">
            View registrations
        </a>
    </p>
    <?php if ($flash !== null): ?>
        <div class="<?= escape($flash['type']) ?>"><?= escape($flash['message']) ?></div>
    <?php endif; ?>

    <form action="../actions/save-workshop.php" method="post" class="card mb-20">
        <?= csrf_field() ?>
        <h2>Add workshop</h2>
        <input name="workshop_title" class="form-control" placeholder="Workshop title" required maxlength="150">
        <textarea name="description" class="form-control" placeholder="Description"></textarea>
        <input name="workshop_date" type="date" class="form-control" required>
        <input name="start_time" type="time" class="form-control" required>
        <input name="location" class="form-control" placeholder="Location" required maxlength="200">
        <input name="capacity" type="number" min="1" class="form-control" placeholder="Capacity" required>
        <input name="registration_fee" type="number" min="0" step="0.01" class="form-control" placeholder="Fee" required>
        <select name="workshop_status" class="form-control">
            <option value="Scheduled">Scheduled</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select>
        <button type="submit" class="btn btn-primary">Add workshop</button>
    </form>

    <?php foreach ($workshops as $workshop): ?>
        <form action="../actions/save-workshop.php" method="post" class="card mb-20">
            <?= csrf_field() ?>
            <input type="hidden" name="workshop_id" value="<?= (int) $workshop['workshop_id'] ?>">
            <h2><?= escape($workshop['workshop_title']) ?></h2>
            <p>Registered customers: <?= (int) $workshop['registration_count'] ?></p>
            <input name="workshop_title" value="<?= escape($workshop['workshop_title']) ?>" class="form-control" required maxlength="150">
            <textarea name="description" class="form-control"><?= escape((string) $workshop['description']) ?></textarea>
            <input name="workshop_date" type="date" value="<?= escape($workshop['workshop_date']) ?>" class="form-control" required>
            <input name="start_time" type="time" value="<?= escape(substr($workshop['start_time'], 0, 5)) ?>" class="form-control" required>
            <input name="location" value="<?= escape($workshop['location']) ?>" class="form-control" required maxlength="200">
            <input name="capacity" type="number" min="1" value="<?= (int) $workshop['capacity'] ?>" class="form-control" required>
            <input name="registration_fee" type="number" min="0" step="0.01" value="<?= escape((string) $workshop['registration_fee']) ?>" class="form-control" required>
            <select name="workshop_status" class="form-control">
                <?php foreach (['Scheduled', 'Completed', 'Cancelled'] as $status): ?>
                    <option value="<?= $status ?>" <?= $workshop['workshop_status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Save workshop</button>
        </form>
    <?php endforeach; ?>
</main>
</body>
</html>
