<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!request_is_post()) {
    redirect('/uni/ecosprout/cart.php');
}

verify_csrf();

$quantities = $_POST['quantities'] ?? [];

if (!is_array($quantities)) {
    set_flash('error', 'Invalid cart information.');
    redirect('/uni/ecosprout/cart.php');
}

foreach ($quantities as $plantId => $quantity) {
    $plantId = filter_var(
        $plantId,
        FILTER_VALIDATE_INT
    );

    $quantity = filter_var(
        $quantity,
        FILTER_VALIDATE_INT
    );

    if (!$plantId || !isset($_SESSION['cart'][$plantId])) {
        continue;
    }

    if (!$quantity || $quantity < 1) {
        unset($_SESSION['cart'][$plantId]);
        continue;
    }

    $stockQuery = $pdo->prepare(
        'SELECT stock_quantity
         FROM plants
         WHERE plant_id = :plant_id
           AND plant_status = \'Active\'
         LIMIT 1'
    );

    $stockQuery->execute([
        'plant_id' => $plantId,
    ]);

    $stock = $stockQuery->fetchColumn();

    if ($stock === false) {
        unset($_SESSION['cart'][$plantId]);
        continue;
    }

    $_SESSION['cart'][$plantId] = min(
        $quantity,
        (int) $stock
    );
}

set_flash('success', 'Your cart was updated.');

redirect('/uni/ecosprout/cart.php');