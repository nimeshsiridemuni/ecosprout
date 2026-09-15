<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Administrator']);

$totalQuery = $pdo->query(
    'SELECT
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM plants) AS total_plants,
        (SELECT COUNT(*) FROM orders) AS total_orders,
        (
            SELECT COUNT(*)
            FROM service_bookings
        ) AS total_bookings,
        (
            SELECT COUNT(*)
            FROM inquiries
            WHERE inquiry_status = \'New\'
        ) AS new_inquiries'
);

$totals = $totalQuery->fetch();

$salesQuery = $pdo->query(
    'SELECT
        COUNT(*) AS completed_orders,
        COALESCE(SUM(total_amount), 0) AS total_sales
     FROM orders
     WHERE order_status = \'Completed\''
);

$sales = $salesQuery->fetch();

$popularPlantQuery = $pdo->query(
    'SELECT
        p.plant_name,
        SUM(oi.quantity) AS quantity_sold,
        SUM(oi.line_total) AS sales_value
     FROM order_items AS oi
     INNER JOIN plants AS p
        ON p.plant_id = oi.plant_id
     INNER JOIN orders AS o
        ON o.order_id = oi.order_id
     WHERE o.order_status <> \'Cancelled\'
     GROUP BY p.plant_id, p.plant_name
     ORDER BY quantity_sold DESC
     LIMIT 10'
);

$popularPlants = $popularPlantQuery->fetchAll();

$lowStockQuery = $pdo->query(
    'SELECT
        plant_name,
        stock_quantity
     FROM plants
     WHERE stock_quantity <= 5
       AND plant_status <> \'Inactive\'
     ORDER BY stock_quantity'
);

$lowStockPlants = $lowStockQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reports | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="dashboard.php">&larr; Admin dashboard</a>
        </p>

        <h1>System Reports</h1>

        <section class="grid-4 mb-20">
            <article class="card">
                <h2><?= (int) $totals['total_users'] ?></h2>
                <p>Total users</p>
            </article>

            <article class="card">
                <h2><?= (int) $totals['total_plants'] ?></h2>
                <p>Total plants</p>
            </article>

            <article class="card">
                <h2><?= (int) $totals['total_orders'] ?></h2>
                <p>Total orders</p>
            </article>

            <article class="card">
                <h2><?= (int) $totals['total_bookings'] ?></h2>
                <p>Service bookings</p>
            </article>
        </section>

        <section class="card mb-20">
            <h2>Completed sales</h2>

            <p>
                Completed orders:
                <?= (int) $sales['completed_orders'] ?>
            </p>

            <p>
                Sales total:
                <strong>
                    LKR
                    <?= number_format(
                        (float) $sales['total_sales'],
                        2
                    ) ?>
                </strong>
            </p>

            <p>
                New inquiries:
                <?= (int) $totals['new_inquiries'] ?>
            </p>
        </section>

        <section class="card mb-20">
            <h2>Best-selling plants</h2>

            <?php if ($popularPlants === []): ?>
                <p>No sales information is available.</p>
            <?php else: ?>
                <?php foreach ($popularPlants as $plant): ?>
                    <p>
                        <?= escape($plant['plant_name']) ?>:
                        <?= (int) $plant['quantity_sold'] ?>
                        sold — LKR
                        <?= number_format(
                            (float) $plant['sales_value'],
                            2
                        ) ?>
                    </p>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="card">
            <h2>Low-stock plants</h2>

            <?php if ($lowStockPlants === []): ?>
                <p>No low-stock plants were found.</p>
            <?php else: ?>
                <?php foreach ($lowStockPlants as $plant): ?>
                    <p>
                        <?= escape($plant['plant_name']) ?>:
                        <?= (int) $plant['stock_quantity'] ?>
                        remaining
                    </p>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>