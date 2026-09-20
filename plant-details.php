<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$plantId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$plantId) {
    http_response_code(404);
    exit('Plant not found.');
}

$plantQuery = $pdo->prepare(
    'SELECT
        p.plant_id,
        p.plant_name,
        p.scientific_name,
        p.description,
        p.care_instructions,
        p.price,
        p.stock_quantity,
        p.image_name,
        c.category_name
     FROM plants AS p
     INNER JOIN categories AS c
        ON c.category_id = p.category_id
     WHERE p.plant_id = :plant_id
       AND p.plant_status = \'Active\'
     LIMIT 1'
);

$plantQuery->execute([
    'plant_id' => $plantId,
]);

$plant = $plantQuery->fetch();

if (!$plant) {
    http_response_code(404);
    exit('Plant not found.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= escape($plant['plant_name']) ?> | EcoSprout
    </title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="plants.php">&larr; Back to plants</a>
        </p>

        <article class="card">
            <span class="badge badge-green">
                <?= escape($plant['category_name']) ?>
            </span>

            <?php if (
                !empty($plant['image_name'])
                && is_file(
                    __DIR__ . '/assets/images/plants/'
                    . basename((string) $plant['image_name'])
                )
            ): ?>
                <img src="assets/images/plants/<?= escape(
                    basename((string) $plant['image_name'])
                ) ?>" alt="<?= escape($plant['plant_name']) ?>" style="
                        display: block;
                        width: 100%;
                        max-height: 420px;
                        object-fit: cover;
                        border-radius: 8px;
                        margin: 20px 0;
                    ">
            <?php endif; ?>

            <h1>
                <?= escape($plant['plant_name']) ?>
            </h1>

            <?php if (!empty($plant['scientific_name'])): ?>
                <p class="text-secondary">
                    <em>
                        <?= escape($plant['scientific_name']) ?>
                    </em>
                </p>
            <?php endif; ?>

            <p>
                <?= nl2br(
                    escape($plant['description'] ?? '')
                ) ?>
            </p>

            <h2>Care instructions</h2>

            <p>
                <?= nl2br(
                    escape($plant['care_instructions'] ?? '')
                ) ?>
            </p>

            <p>
                Available stock:
                <?= (int) $plant['stock_quantity'] ?>
            </p>

            <strong class="text-green">
                LKR
                <?= number_format(
                    (float) $plant['price'],
                    2
                ) ?>
            </strong>
        </article>
    </main>
</body>

</html>
