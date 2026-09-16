<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/checkout.php');
}

verify_csrf();

$deliveryAddress = trim(
    $_POST['delivery_address'] ?? ''
);
$customerName = trim($_POST['customer_name'] ?? '');
$customerPhone = trim($_POST['customer_phone'] ?? '');

$paymentMethod = $_POST['payment_method'] ?? '';
$cardNumber = preg_replace('/\D+/', '', $_POST['card_number'] ?? '');
$cardExpiry = trim($_POST['card_expiry'] ?? '');
$cardCvv = preg_replace('/\D+/', '', $_POST['card_cvv'] ?? '');

$allowedPaymentMethods = [
    'Cash on Delivery',
    'Bank Transfer',
    'Card Payment',
];

if (
    $customerName === ''
    || strlen($customerName) > 100
    || $customerPhone === ''
    || strlen($customerPhone) > 20
    || $deliveryAddress === ''
    || strlen($deliveryAddress) > 255
    || !in_array(
        $paymentMethod,
        $allowedPaymentMethods,
        true
    )
) {
    set_flash(
        'error',
        'Please enter valid delivery and payment details.'
    );

    redirect('/uni/ecosprout/checkout.php');
}

if ($paymentMethod === 'Card Payment') {
    if (
        strlen($cardNumber) < 13
        || strlen($cardNumber) > 19
        || !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $cardExpiry)
        || !preg_match('/^\d{3,4}$/', $cardCvv)
    ) {
        set_flash('error', 'Please enter valid card payment details.');
        redirect('/uni/ecosprout/checkout.php');
    }

    $digits = strrev($cardNumber);
    $checksum = 0;

    for ($index = 0; $index < strlen($digits); $index++) {
        $digit = (int) $digits[$index];

        if ($index % 2 === 1) {
            $digit *= 2;
            $digit = $digit > 9 ? $digit - 9 : $digit;
        }

        $checksum += $digit;
    }

    if ($checksum % 10 !== 0) {
        set_flash('error', 'Please enter a valid card number.');
        redirect('/uni/ecosprout/checkout.php');
    }
}

$cart = $_SESSION['cart'] ?? [];

if ($cart === []) {
    set_flash('error', 'Your cart is empty.');
    redirect('/uni/ecosprout/cart.php');
}

$deliveryFee = 500.00;

try {
    $pdo->beginTransaction();

    $orderPlants = [];
    $subtotal = 0.0;

    $plantQuery = $pdo->prepare(
        'SELECT
            plant_id,
            plant_name,
            price,
            stock_quantity
         FROM plants
         WHERE plant_id = :plant_id
           AND plant_status = \'Active\'
         FOR UPDATE'
    );

    foreach ($cart as $plantId => $quantity) {
        $plantId = (int) $plantId;
        $quantity = (int) $quantity;

        if ($plantId < 1 || $quantity < 1) {
            throw new RuntimeException(
                'Invalid item in the shopping cart.'
            );
        }

        $plantQuery->execute([
            'plant_id' => $plantId,
        ]);

        $plant = $plantQuery->fetch();

        if (!$plant) {
            throw new RuntimeException(
                'A selected plant is no longer available.'
            );
        }

        if ($quantity > (int) $plant['stock_quantity']) {
            throw new RuntimeException(
                'There is insufficient stock for ' .
                $plant['plant_name'] . '.'
            );
        }

        $unitPrice = (float) $plant['price'];
        $lineTotal = $unitPrice * $quantity;
        $subtotal += $lineTotal;

        $orderPlants[] = [
            'plant_id' => $plantId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $lineTotal,
        ];
    }

    if ($orderPlants === []) {
        throw new RuntimeException(
            'Your cart does not contain any available plants.'
        );
    }

    $totalAmount = $subtotal + $deliveryFee;

    $orderQuery = $pdo->prepare(
        'INSERT INTO orders (
            customer_id,
            customer_name,
            customer_phone,
            delivery_address,
            subtotal,
            delivery_fee,
            total_amount,
            order_status
         ) VALUES (
            :customer_id,
            :customer_name,
            :customer_phone,
            :delivery_address,
            :subtotal,
            :delivery_fee,
            :total_amount,
            \'Pending\'
         )'
    );

    $orderQuery->execute([
        'customer_id' => (int) $_SESSION['user_id'],
        'customer_name' => $customerName,
        'customer_phone' => $customerPhone,
        'delivery_address' => $deliveryAddress,
        'subtotal' => $subtotal,
        'delivery_fee' => $deliveryFee,
        'total_amount' => $totalAmount,
    ]);

    $orderId = (int) $pdo->lastInsertId();

    $itemQuery = $pdo->prepare(
        'INSERT INTO order_items (
            order_id,
            plant_id,
            quantity,
            unit_price,
            line_total
         ) VALUES (
            :order_id,
            :plant_id,
            :quantity,
            :unit_price,
            :line_total
         )'
    );

    $stockQuery = $pdo->prepare(
        'UPDATE plants
         SET
            stock_quantity = stock_quantity - :decrement_quantity,
            plant_status = CASE
                WHEN stock_quantity - :remaining_quantity = 0
                    THEN \'Out of Stock\'
                ELSE plant_status
            END
         WHERE plant_id = :plant_id
           AND stock_quantity >= :minimum_quantity'
    );

    foreach ($orderPlants as $plant) {
        $itemQuery->execute([
            'order_id' => $orderId,
            'plant_id' => $plant['plant_id'],
            'quantity' => $plant['quantity'],
            'unit_price' => $plant['unit_price'],
            'line_total' => $plant['line_total'],
        ]);

        $stockQuery->execute([
            'decrement_quantity' => $plant['quantity'],
            'remaining_quantity' => $plant['quantity'],
            'minimum_quantity' => $plant['quantity'],
            'plant_id' => $plant['plant_id'],
        ]);

        if ($stockQuery->rowCount() !== 1) {
            throw new RuntimeException(
                'Stock changed while the order was being placed.'
            );
        }
    }

    $paymentStatus = $paymentMethod === 'Card Payment'
        ? 'Paid'
        : 'Pending';

    $transactionReference =
        'ECO-' . $orderId . '-' . strtoupper(
            bin2hex(random_bytes(3))
        );

    $paidAt = $paymentStatus === 'Paid'
        ? date('Y-m-d H:i:s')
        : null;

    $paymentQuery = $pdo->prepare(
        'INSERT INTO payments (
            order_id,
            payment_method,
            amount,
            payment_status,
            transaction_reference,
            paid_at
         ) VALUES (
            :order_id,
            :payment_method,
            :amount,
            :payment_status,
            :transaction_reference,
            :paid_at
         )'
    );

    $paymentQuery->execute([
        'order_id' => $orderId,
        'payment_method' => $paymentMethod,
        'amount' => $totalAmount,
        'payment_status' => $paymentStatus,
        'transaction_reference' => $transactionReference,
        'paid_at' => $paidAt,
    ]);

    $pdo->commit();

    unset($_SESSION['cart']);

    set_flash(
        'success',
        'Order #' . $orderId . ' was placed successfully.'
    );

    redirect('/uni/ecosprout/customer/orders.php');
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($exception->getMessage());

    set_flash(
        'error',
        $exception instanceof RuntimeException
        ? $exception->getMessage()
        : 'The order could not be placed. Please try again.'
    );

    redirect('/uni/ecosprout/checkout.php');
}