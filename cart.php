<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();
$cart = $_SESSION['cart'] ?? [];
$plants = [];
$cartTotal = 0.0;

if ($cart !== []) {
    $plantIds = array_map(
        'intval',
        array_keys($cart)
    );

    $placeholders = implode(
        ',',
        array_fill(0, count($plantIds), '?')
    );

    $cartQuery = $pdo->prepare(
        "SELECT
            plant_id,
            plant_name,
            price,
            stock_quantity
         FROM plants
         WHERE plant_id IN ($placeholders)
           AND plant_status = 'Active'"
    );

    $cartQuery->execute($plantIds);
    $plants = $cartQuery->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Shopping Cart | EcoSprout</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="plants.php">&larr; Continue shopping</a>
        </p>

        <h1>Shopping Cart</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($plants === []): ?>
            <section class="card text-center">
                <h2>Your cart is empty</h2>

                <p class="text-secondary">
                    Browse our catalogue and select a plant.
                </p>

                <a class="btn btn-primary" href="plants.php">
                    Browse plants
                </a>
            </section>
        <?php else: ?>
            <form
                action="actions/update-cart.php"
                method="post"
            >
                <?= csrf_field() ?>

                <div class="card">
                    <?php foreach ($plants as $plant): ?>
                        <?php
                        $plantId = (int) $plant['plant_id'];

                        $quantity = (int) (
                            $cart[$plantId] ?? 1
                        );

                        $lineTotal =
                            (float) $plant['price'] * $quantity;

                        $cartTotal += $lineTotal;
                        ?>

                        <section class="mb-20">
                            <h2>
                                <?= escape($plant['plant_name']) ?>
                            </h2>

                            <p>
                                Unit price:
                                LKR
                                <?= number_format(
                                    (float) $plant['price'],
                                    2
                                ) ?>
                            </p>

                            <label for="quantity-<?= $plantId ?>">
                                Quantity
                            </label>

                            <input
                                id="quantity-<?= $plantId ?>"
                                type="number"
                                name="quantities[<?= $plantId ?>]"
                                value="<?= $quantity ?>"
                                min="1"
                                max="<?= (int) $plant['stock_quantity'] ?>"
                            >

                            <p>
                                Item total:
                                <strong>
                                    LKR
                                    <?= number_format($lineTotal, 2) ?>
                                </strong>
                            </p>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Update cart
                            </button>
                        </section>
                    <?php endforeach; ?>

                    <hr>

                    <h2>
                        Cart total:
                        LKR <?= number_format($cartTotal, 2) ?>
                    </h2>
                    <p class="mt-20">
    <a href="checkout.php" class="btn btn-primary">
        Continue to checkout
    </a>
</p>
                </div>
            </form>

            <?php foreach ($plants as $plant): ?>
                <form
                    action="actions/remove-from-cart.php"
                    method="post"
                    class="mb-20"
                >
                    <?= csrf_field() ?>

                    <input
                        type="hidden"
                        name="plant_id"
                        value="<?= (int) $plant['plant_id'] ?>"
                    >

                    <button type="submit" class="btn btn-outline">
                        Remove
                        <?= escape($plant['plant_name']) ?>
                    </button>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>