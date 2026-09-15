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

foreach ($plants as $plant) {
    $quantity = (int) (
        $cart[(int) $plant['plant_id']] ?? 0
    );

    $subtotal += (float) $plant['price'] * $quantity;
}

$totalAmount = $subtotal + $deliveryFee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

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

            <form
                action="actions/place-order.php"
                method="post"
            >
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="delivery_address">
                        Delivery address
                    </label>

                    <textarea
                        id="delivery_address"
                        name="delivery_address"
                        class="form-control"
                        required
                        maxlength="255"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="payment_method">
                        Payment method
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        class="form-control"
                        required
                    >
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
                            Card Simulation
                        </option>
                    </select>
                </div>

                <p class="text-secondary">
                    Card payment is simulated. Do not enter real
                    card information.
                </p>

                <button type="submit" class="btn btn-primary">
                    Place order
                </button>
            </form>
        </section>
    </main>
</body>
</html>