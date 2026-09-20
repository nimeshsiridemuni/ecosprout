<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

require_role(['Customer']);

$flash = get_flash();
$cart = $_SESSION['cart'] ?? [];
$plants = [];
$subtotal = 0.0;
$deliveryFee = 500.00;

if ($cart === []) {
    set_flash('error', 'Your cart is empty.');
    redirect('/uni/ecosprout/cart.php');
}

$plantIds = array_map('intval', array_keys($cart));

$placeholders = implode(
    ',',
    array_fill(0, count($plantIds), '?')
);

$plantQuery = $pdo->prepare(
    "SELECT
        plant_id,
        plant_name,
        price,
        stock_quantity
     FROM plants
     WHERE plant_id IN ($placeholders)
       AND plant_status = 'Active'"
);

$plantQuery->execute($plantIds);
$plants = $plantQuery->fetchAll();

$availablePlantIds = array_map(
    static fn (array $plant): int => (int) $plant['plant_id'],
    $plants
);
$cartWasAdjusted = false;

foreach ($cart as $plantId => $quantity) {
    if (!in_array((int) $plantId, $availablePlantIds, true)) {
        unset($_SESSION['cart'][$plantId]);
        $cartWasAdjusted = true;
    }
}

foreach ($plants as $plant) {
    $plantId = (int) $plant['plant_id'];
    $quantity = (int) ($cart[$plantId] ?? 0);
    $stockQuantity = (int) $plant['stock_quantity'];

    if ($quantity < 1 || $stockQuantity < 1) {
        unset($_SESSION['cart'][$plantId]);
        $cartWasAdjusted = true;
        continue;
    }

    if ($quantity > $stockQuantity) {
        $_SESSION['cart'][$plantId] = $stockQuantity;
        $cartWasAdjusted = true;
    }

    $quantity = min($quantity, $stockQuantity);

    $subtotal += (float) $plant['price'] * $quantity;
}

if ($cartWasAdjusted) {
    set_flash(
        'error',
        'Your cart was updated because availability changed. Please review it.'
    );
    redirect('/uni/ecosprout/cart.php');
}

$totalAmount = $subtotal + $deliveryFee;
$customerQuery = $pdo->prepare(
    'SELECT full_name, phone, address
     FROM users
     WHERE user_id = :user_id
     LIMIT 1'
);
$customerQuery->execute([
    'user_id' => (int) $_SESSION['user_id'],
]);
$customer = $customerQuery->fetch() ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | EcoSprout</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="cart.php">&larr; Return to cart</a>
        </p>

        <h1>Checkout</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <section class="card mb-20">
            <h2>Order summary</h2>

            <?php foreach ($plants as $plant): ?>
                <?php
                $quantity = (int) (
                    $cart[(int) $plant['plant_id']] ?? 0
                );

                $lineTotal =
                    (float) $plant['price'] * $quantity;
                ?>

                <p>
                    <?= escape($plant['plant_name']) ?>
                    × <?= $quantity ?>

                    — LKR
                    <?= number_format($lineTotal, 2) ?>
                </p>
            <?php endforeach; ?>

            <hr>

            <p>
                Subtotal:
                LKR <?= number_format($subtotal, 2) ?>
            </p>

            <p>
                Delivery:
                LKR <?= number_format($deliveryFee, 2) ?>
            </p>

            <h2>
                Total:
                LKR <?= number_format($totalAmount, 2) ?>
            </h2>
        </section>

        <section class="card">
            <h2>Delivery and payment</h2>

            <form action="actions/place-order.php" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="customer_name">Full name</label>

                    <input id="customer_name" name="customer_name" type="text" class="form-control" maxlength="100"
                        value="<?= escape((string) ($customer['full_name'] ?? '')) ?>" required>
                </div>

                <div class="form-group">
                    <label for="customer_phone">Telephone number</label>

                    <input id="customer_phone" name="customer_phone" type="tel" class="form-control" maxlength="20"
                        value="<?= escape((string) ($customer['phone'] ?? '')) ?>" required>
                </div>

                <div class="form-group">
                    <label for="delivery_address">
                        Delivery address
                    </label>

                    <textarea id="delivery_address" name="delivery_address" class="form-control" required
                        maxlength="255"><?= escape((string) ($customer['address'] ?? '')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="payment_method">
                        Payment method
                    </label>

                    <select id="payment_method" name="payment_method" class="form-control" required>
                        <option value="">
                            Select a method
                        </option>

                        <option value="Cash on Delivery">
                            Cash on Delivery
                        </option>

                        <option value="Bank Transfer">
                            Bank Transfer
                        </option>

                        <option value="Card Simulation">
                            Card Payment (demo)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="card_number">Card number</label>
                    <input id="card_number" name="card_number" type="text" class="form-control" inputmode="numeric"
                        autocomplete="cc-number" maxlength="19" placeholder="1234 5678 9012 3456">
                </div>

                <div class="flex gap-10">
                    <div class="form-group">
                        <label for="card_expiry">Expiry (MM/YY)</label>
                        <input id="card_expiry" name="card_expiry" type="text" class="form-control"
                            autocomplete="cc-exp" maxlength="5" placeholder="MM/YY">
                    </div>

                    <div class="form-group">
                        <label for="card_cvv">CVV</label>
                        <input id="card_cvv" name="card_cvv" type="password" class="form-control" inputmode="numeric"
                            autocomplete="cc-csc" maxlength="4">
                    </div>
                </div>

                <p class="text-secondary">
                    This is a classroom payment simulation. Use test card
                    details only; EcoSprout does not store them.
                </p>

                <button type="submit" class="btn btn-primary">
                    Place order
                </button>
            </form>
        </section>
    </main>
</body>

</html>
