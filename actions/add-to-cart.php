<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!request_is_post()) {
    redirect('/uni/ecosprout/plants.php');
}

verify_csrf();

$plantId = filter_input(
    INPUT_POST,
    'plant_id',
    FILTER_VALIDATE_INT
);

$quantity = filter_input(
    INPUT_POST,
    'quantity',
    FILTER_VALIDATE_INT
);

if (!$plantId || !$quantity || $quantity < 1) {
    set_flash('error', 'Invalid plant or quantity.');
    redirect('/uni/ecosprout/plants.php');
}

$plantQuery = $pdo->prepare(
    'SELECT plant_id, plant_name, stock_quantity
     FROM plants
     WHERE plant_id = :plant_id
       AND plant_status = \'Active\'
     LIMIT 1'
);

$plantQuery->execute([
    'plant_id' => $plantId,
]);

$plant = $plantQuery->fetch();

if (!$plant) {
    set_flash('error', 'The selected plant is unavailable.');
    redirect('/uni/ecosprout/plants.php');
}

$currentQuantity = (int) (
    $_SESSION['cart'][$plantId] ?? 0
);

$newQuantity = $currentQuantity + $quantity;

if ($newQuantity > (int) $plant['stock_quantity']) {
    set_flash(
        'error',
        'The requested quantity is greater than the available stock.'
    );

    redirect('/uni/ecosprout/plants.php');
}

$_SESSION['cart'][$plantId] = $newQuantity;

set_flash(
    'success',
    $plant['plant_name'] . ' was added to your cart.'
);

redirect('/uni/ecosprout/cart.php');