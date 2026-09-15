<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

$flash = get_flash();

$orderQuery = $pdo->query(
    'SELECT
        o.order_id,
        o.order_date,
        o.total_amount,
        o.order_status,
        u.full_name,
        u.email,
        p.payment_status
     FROM orders AS o
     INNER JOIN users AS u
        ON u.user_id = o.customer_id
     LEFT JOIN payments AS p
        ON p.order_id = o.order_id
     ORDER BY o.order_date DESC'
);

$orders = $orderQuery->fetchAll();

$statuses = [
    'Pending',
    'Confirmed',
    'Processing',
    'Dispatched',
    'Completed',
    'Cancelled',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Orders | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p><a href="dashboard.php">&larr; Dashboard</a></p>

        <h1>Customer Orders</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php foreach ($orders as $order): ?>
            <article class="card mb-20">
                <h2>
                    Order #<?= (int) $order['order_id'] ?>
                </h2>

                <p>
                    Customer:
                    <?= escape($order['full_name']) ?>
                    (<?= escape($order['email']) ?>)
                </p>

                <p>
                    Total: LKR
                    <?= number_format(
                        (float) $order['total_amount'],
                        2
                    ) ?>
                </p>

                <p>
                    Payment:
                    <?= escape($order['payment_status'] ?? 'Pending') ?>
                </p>

                <form
                    action="../actions/update-order-status.php"
                    method="post"
                >
                    <?= csrf_field() ?>

                    <input
                        type="hidden"
                        name="order_id"
                        value="<?= (int) $order['order_id'] ?>"
                    >

                    <label>
                        Order status

                        <select
                            name="order_status"
                            class="form-control"
                        >
                            <?php foreach ($statuses as $status): ?>
                                <option
                                    value="<?= $status ?>"
                                    <?= $order['order_status'] === $status
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= $status ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <button type="submit" class="btn btn-primary">
                        Update status
                    </button>
                </form>
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>