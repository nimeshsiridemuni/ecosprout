<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

$plantId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

$plant = [
    'plant_id' => '',
    'category_id' => '',
    'plant_name' => '',
    'scientific_name' => '',
    'description' => '',
    'care_instructions' => '',
    'price' => '',
    'stock_quantity' => '',
    'image_name' => '',
    'plant_status' => 'Active',
];

if ($plantId) {
    $plantQuery = $pdo->prepare(
        'SELECT *
         FROM plants
         WHERE plant_id = :plant_id
         LIMIT 1'
    );

    $plantQuery->execute([
        'plant_id' => $plantId,
    ]);

    $existingPlant = $plantQuery->fetch();

    if (!$existingPlant) {
        http_response_code(404);
        exit('Plant not found.');
    }

    $plant = $existingPlant;
}

$categoryQuery = $pdo->query(
    'SELECT category_id, category_name
     FROM categories
     ORDER BY category_name'
);

$categories = $categoryQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= $plantId ? 'Edit' : 'Add' ?> Plant | EcoSprout
    </title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p><a href="plants.php">&larr; Manage plants</a></p>

        <h1><?= $plantId ? 'Edit Plant' : 'Add Plant' ?></h1>

        <form
            action="../actions/save-plant.php"
            method="post"
            class="card"
        >
            <?= csrf_field() ?>

            <?php if ($plantId): ?>
                <input
                    type="hidden"
                    name="plant_id"
                    value="<?= (int) $plant['plant_id'] ?>"
                >
            <?php endif; ?>

            <label for="category_id">Category</label>

            <select
                id="category_id"
                name="category_id"
                class="form-control"
                required
            >
                <option value="">Select category</option>

                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?= (int) $category['category_id'] ?>"
                        <?= (int) $plant['category_id']
                            === (int) $category['category_id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= escape($category['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="plant_name">Plant name</label>

            <input
                id="plant_name"
                name="plant_name"
                type="text"
                class="form-control"
                maxlength="100"
                value="<?= escape((string) $plant['plant_name']) ?>"
                required
            >

            <label for="scientific_name">Scientific name</label>

            <input
                id="scientific_name"
                name="scientific_name"
                type="text"
                class="form-control"
                maxlength="120"
                value="<?= escape((string) $plant['scientific_name']) ?>"
            >

            <label for="description">Description</label>

            <textarea
                id="description"
                name="description"
                class="form-control"
                maxlength="2000"
            ><?= escape((string) $plant['description']) ?></textarea>

            <label for="care_instructions">
                Care instructions
            </label>

            <textarea
                id="care_instructions"
                name="care_instructions"
                class="form-control"
                maxlength="2000"
            ><?= escape((string) $plant['care_instructions']) ?></textarea>

            <label for="price">Price (LKR)</label>

            <input
                id="price"
                name="price"
                type="number"
                class="form-control"
                min="0"
                step="0.01"
                value="<?= escape((string) $plant['price']) ?>"
                required
            >

            <label for="stock_quantity">Stock quantity</label>

            <input
                id="stock_quantity"
                name="stock_quantity"
                type="number"
                class="form-control"
                min="0"
                value="<?= escape((string) $plant['stock_quantity']) ?>"
                required
            >

            <label for="image_name">Image filename</label>

            <input
                id="image_name"
                name="image_name"
                type="text"
                class="form-control"
                maxlength="255"
                value="<?= escape((string) $plant['image_name']) ?>"
                placeholder="peace-lily.jpg"
            >

            <label for="plant_status">Status</label>

            <select
                id="plant_status"
                name="plant_status"
                class="form-control"
                required
            >
                <?php foreach (
                    ['Active', 'Inactive', 'Out of Stock']
                    as $status
                ): ?>
                    <option
                        value="<?= $status ?>"
                        <?= $plant['plant_status'] === $status
                            ? 'selected'
                            : '' ?>
                    >
                        <?= $status ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">
                Save plant
            </button>
        </form>
    </main>
</body>
</html>