<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!request_is_post()) {
    redirect('/uni/ecosprout/cart.php');
}

verify_csrf();

$plantId = filter_input(
    INPUT_POST,
    'plant_id',
    FILTER_VALIDATE_INT
);

if ($plantId && isset($_SESSION['cart'][$plantId])) {
    unset($_SESSION['cart'][$plantId]);

    set_flash('success', 'The plant was removed from your cart.');
}

redirect('/uni/ecosprout/cart.php');