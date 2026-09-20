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

try {
    $pdo->beginTransaction();

    $currentOrderQuery = $pdo->prepare(
        'SELECT order_status
         FROM orders
         WHERE order_id = :order_id
         FOR UPDATE'
    );
    $currentOrderQuery->execute([
        'order_id' => $orderId,
    ]);

    $currentStatus = $currentOrderQuery->fetchColumn();

    if ($currentStatus === false) {
        throw new RuntimeException('The order could not be found.');
    }

    if ($currentStatus === 'Cancelled' && $orderStatus !== 'Cancelled') {
        throw new RuntimeException(
            'A cancelled order cannot be reopened because its stock was restored.'
        );
    }

    if ($currentStatus !== 'Cancelled' && $orderStatus === 'Cancelled') {
        $itemQuery = $pdo->prepare(
            'SELECT plant_id, quantity
             FROM order_items
             WHERE order_id = :order_id'
        );
        $itemQuery->execute([
            'order_id' => $orderId,
        ]);

        $restoreStockQuery = $pdo->prepare(
            'UPDATE plants
             SET
                stock_quantity = stock_quantity + :quantity,
                plant_status = CASE
                    WHEN plant_status = \'Out of Stock\' THEN \'Active\'
                    ELSE plant_status
                END
             WHERE plant_id = :plant_id'
        );

        foreach ($itemQuery->fetchAll() as $item) {
            $restoreStockQuery->execute([
                'quantity' => (int) $item['quantity'],
                'plant_id' => (int) $item['plant_id'],
            ]);
        }

        $paymentQuery = $pdo->prepare(
            'UPDATE payments
             SET payment_status = CASE
                WHEN payment_status = \'Paid\' THEN \'Refunded\'
                ELSE payment_status
             END
             WHERE order_id = :order_id'
        );
        $paymentQuery->execute([
            'order_id' => $orderId,
        ]);
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

    $pdo->commit();
    set_flash('success', 'Order status updated successfully.');
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if (!$exception instanceof RuntimeException) {
        error_log($exception->getMessage());
    }

    set_flash(
        'error',
        $exception instanceof RuntimeException
            ? $exception->getMessage()
            : 'The order status could not be updated.'
    );
}

redirect('/uni/ecosprout/staff/orders.php');
