<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/staff/orders.php');
}

verify_csrf();

$orderId = filter_input(
    INPUT_POST,
    'order_id',
    FILTER_VALIDATE_INT
);

$orderStatus = $_POST['order_status'] ?? '';

$allowedStatuses = [
    'Pending',
    'Confirmed',
    'Processing',
    'Dispatched',
    'Completed',
    'Cancelled',
];

if (
    !$orderId
    || !in_array($orderStatus, $allowedStatuses, true)
) {
    set_flash('error', 'Invalid order update.');
    redirect('/uni/ecosprout/staff/orders.php');
}

$orderQuery = $pdo->prepare(
    'UPDATE orders
     SET order_status = :order_status
     WHERE order_id = :order_id'
);

$orderQuery->execute([
    'order_status' => $orderStatus,
    'order_id' => $orderId,
]);

set_flash('success', 'Order status updated successfully.');

redirect('/uni/ecosprout/staff/orders.php');