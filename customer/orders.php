<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

$flash = get_flash();

$orderQuery = $pdo->prepare(
    'SELECT
        o.order_id,
        o.order_date,
        o.total_amount,
        o.order_status,
        p.payment_method,
        p.payment_status
     FROM orders AS o
     LEFT JOIN payments AS p
        ON p.order_id = o.order_id
     WHERE o.customer_id = :customer_id
     ORDER BY o.order_date DESC'
);

$orderQuery->execute([
    'customer_id' => (int) $_SESSION['user_id'],
]);

$orders = $orderQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Orders | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="../plants.php">&larr; Browse plants</a>
        </p>

        <h1>My Orders</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>" role="status" style="font-size: 1.1rem; margin-bottom: 20px;">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($flash !== null && $flash['type'] === 'success'): ?>
            <section class="card mb-20">
                <h2>Purchase successful</h2>
                <p>
                    Thank you for your purchase. Your order has been
                    received and is now being processed.
                </p>
            </section>
        <?php endif; ?>

        <?php if ($orders === []): ?>
            <section class="card">
                <p>You have not placed any orders yet.</p>
            </section>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <article class="card mb-20">
                    <h2>
                        Order #<?= (int) $order['order_id'] ?>
                    </h2>

                    <p>
                        Date:
                        <?= escape($order['order_date']) ?>
                    </p>

                    <p>
                        Total:
                        LKR
                        <?= number_format(
                            (float) $order['total_amount'],
                            2
                        ) ?>
                    </p>

                    <p>
                        Order status:
                        <?= escape($order['order_status']) ?>
                    </p>

                    <p>
                        Payment:
                        <?= escape(
                            $order['payment_method'] ?? 'Not recorded'
                        ) ?>
                        —
                        <?= escape(
                            $order['payment_status'] ?? 'Pending'
                        ) ?>
                    </p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>

</html>