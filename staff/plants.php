<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

$flash = get_flash();

$plantQuery = $pdo->query(
    'SELECT
        p.plant_id,
        p.plant_name,
        p.price,
        p.stock_quantity,
        p.plant_status,
        c.category_name
     FROM plants AS p
     INNER JOIN categories AS c
        ON c.category_id = p.category_id
     ORDER BY p.plant_name'
);

$plants = $plantQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Plants | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p><a href="dashboard.php">&larr; Dashboard</a></p>

        <div class="flex justify-between align-center mb-20">
            <h1>Manage Plants</h1>

            <a href="plant-form.php" class="btn btn-primary">
                Add plant
            </a>
        </div>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <section class="card" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Plant</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($plants as $plant): ?>
                        <tr>
                            <td>
                                <?= escape($plant['plant_name']) ?>
                            </td>

                            <td>
                                <?= escape($plant['category_name']) ?>
                            </td>

                            <td>
                                LKR
                                <?= number_format(
                                    (float) $plant['price'],
                                    2
                                ) ?>
                            </td>

                            <td>
                                <?= (int) $plant['stock_quantity'] ?>
                            </td>

                            <td>
                                <?= escape($plant['plant_status']) ?>
                            </td>

                            <td>
                                <a
                                    href="plant-form.php?id=<?= (int) $plant['plant_id'] ?>"
                                    class="btn btn-outline"
                                >
                                    Edit
                                </a>

                                <?php if (
                                    $plant['plant_status'] !== 'Inactive'
                                ): ?>
                                    <form
                                        action="../actions/deactivate-plant.php"
                                        method="post"
                                        style="display: inline;"
                                    >
                                        <?= csrf_field() ?>

                                        <input
                                            type="hidden"
                                            name="plant_id"
                                            value="<?= (int) $plant['plant_id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-outline"
                                        >
                                            Deactivate
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>