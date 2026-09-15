<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_role(['Staff', 'Administrator']);

$flash = get_flash();
$services = $pdo->query('SELECT * FROM services ORDER BY service_name')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services | EcoSprout</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<main class="container mt-40">
    <p><a href="dashboard.php">&larr; Dashboard</a></p>
    <h1>Manage Services</h1>
    <?php if ($flash !== null): ?>
        <div class="<?= escape($flash['type']) ?>"><?= escape($flash['message']) ?></div>
    <?php endif; ?>

    <form action="../actions/save-service.php" method="post" class="card mb-20">
        <?= csrf_field() ?>
        <h2>Add service</h2>
        <input name="service_name" class="form-control" placeholder="Service name" required maxlength="100">
        <textarea name="description" class="form-control" placeholder="Description"></textarea>
        <input name="base_price" type="number" min="0" step="0.01" class="form-control" placeholder="Base price" required>
        <input name="duration_minutes" type="number" min="1" class="form-control" placeholder="Duration in minutes" required>
        <select name="service_status" class="form-control">
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select>
        <button type="submit" class="btn btn-primary">Add service</button>
    </form>

    <?php foreach ($services as $service): ?>
        <form action="../actions/save-service.php" method="post" class="card mb-20">
            <?= csrf_field() ?>
            <input type="hidden" name="service_id" value="<?= (int) $service['service_id'] ?>">
            <input name="service_name" value="<?= escape($service['service_name']) ?>" class="form-control" required maxlength="100">
            <textarea name="description" class="form-control"><?= escape((string) $service['description']) ?></textarea>
            <input name="base_price" type="number" min="0" step="0.01" value="<?= escape((string) $service['base_price']) ?>" class="form-control" required>
            <input name="duration_minutes" type="number" min="1" value="<?= (int) $service['duration_minutes'] ?>" class="form-control" required>
            <select name="service_status" class="form-control">
                <?php foreach (['Available', 'Unavailable'] as $status): ?>
                    <option value="<?= $status ?>" <?= $service['service_status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Save service</button>
        </form>
    <?php endforeach; ?>
</main>
</body>
</html>
