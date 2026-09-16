<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/staff/plants.php');
}

verify_csrf();

$plantId = filter_input(
    INPUT_POST,
    'plant_id',
    FILTER_VALIDATE_INT
);

$categoryId = filter_input(
    INPUT_POST,
    'category_id',
    FILTER_VALIDATE_INT
);

$plantName = trim($_POST['plant_name'] ?? '');
$scientificName = trim($_POST['scientific_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$careInstructions = trim($_POST['care_instructions'] ?? '');
$plantStatus = $_POST['plant_status'] ?? '';

$price = filter_input(
    INPUT_POST,
    'price',
    FILTER_VALIDATE_FLOAT
);

$stockQuantity = filter_input(
    INPUT_POST,
    'stock_quantity',
    FILTER_VALIDATE_INT
);

$allowedStatuses = [
    'Active',
    'Inactive',
    'Out of Stock',
];

if (
    !$categoryId
    || $plantName === ''
    || strlen($plantName) > 100
    || $price === null
    || $price === false
    || $price < 0
    || $stockQuantity === null
    || $stockQuantity === false
    || $stockQuantity < 0
    || !in_array($plantStatus, $allowedStatuses, true)
) {
    set_flash('error', 'Please enter valid plant information.');
    redirect('/uni/ecosprout/staff/plants.php');
}

$categoryQuery = $pdo->prepare(
    'SELECT category_id
     FROM categories
     WHERE category_id = :category_id'
);

$categoryQuery->execute([
    'category_id' => $categoryId,
]);

if (!$categoryQuery->fetch()) {
    set_flash('error', 'The selected category does not exist.');
    redirect('/uni/ecosprout/staff/plants.php');
}

if ($stockQuantity === 0 && $plantStatus === 'Active') {
    $plantStatus = 'Out of Stock';
}

$imageName = null;

if ($plantId) {
    $currentImageQuery = $pdo->prepare(
        'SELECT image_name
         FROM plants
         WHERE plant_id = :plant_id
         LIMIT 1'
    );

    $currentImageQuery->execute([
        'plant_id' => $plantId,
    ]);

    $imageName = $currentImageQuery->fetchColumn() ?: null;
}

if (
    isset($_FILES['plant_image'])
    && $_FILES['plant_image']['error'] !== UPLOAD_ERR_NO_FILE
) {
    $uploadedImage = $_FILES['plant_image'];

    if ($uploadedImage['error'] !== UPLOAD_ERR_OK) {
        set_flash('error', 'The plant image could not be uploaded.');
        redirect('/uni/ecosprout/staff/plants.php');
    }

    if ($uploadedImage['size'] > 5 * 1024 * 1024) {
        set_flash('error', 'Plant images must be 5 MB or smaller.');
        redirect('/uni/ecosprout/staff/plants.php');
    }

    $imageInfo = getimagesize($uploadedImage['tmp_name']);
    $extensionByMime = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (
        $imageInfo === false
        || !isset($extensionByMime[$imageInfo['mime']])
    ) {
        set_flash(
            'error',
            'Only PNG, JPG and WEBP plant images are allowed.'
        );
        redirect('/uni/ecosprout/staff/plants.php');
    }

    $uploadDirectory = __DIR__ . '/../assets/images/plants';

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $imageName = 'plant-' . bin2hex(random_bytes(12)) . '.'
        . $extensionByMime[$imageInfo['mime']];

    if (
        !move_uploaded_file(
            $uploadedImage['tmp_name'],
            $uploadDirectory . '/' . $imageName
        )
    ) {
        set_flash('error', 'The plant image could not be saved.');
        redirect('/uni/ecosprout/staff/plants.php');
    }
}

$values = [
    'category_id' => $categoryId,
    'plant_name' => $plantName,
    'scientific_name' => $scientificName ?: null,
    'description' => $description ?: null,
    'care_instructions' => $careInstructions ?: null,
    'price' => $price,
    'stock_quantity' => $stockQuantity,
    'image_name' => $imageName ?: null,
    'plant_status' => $plantStatus,
];

if ($plantId) {
    $values['plant_id'] = $plantId;

    $saveQuery = $pdo->prepare(
        'UPDATE plants
         SET
            category_id = :category_id,
            plant_name = :plant_name,
            scientific_name = :scientific_name,
            description = :description,
            care_instructions = :care_instructions,
            price = :price,
            stock_quantity = :stock_quantity,
            image_name = :image_name,
            plant_status = :plant_status
         WHERE plant_id = :plant_id'
    );
} else {
    $saveQuery = $pdo->prepare(
        'INSERT INTO plants (
            category_id,
            plant_name,
            scientific_name,
            description,
            care_instructions,
            price,
            stock_quantity,
            image_name,
            plant_status
         ) VALUES (
            :category_id,
            :plant_name,
            :scientific_name,
            :description,
            :care_instructions,
            :price,
            :stock_quantity,
            :image_name,
            :plant_status
         )'
    );
}

$saveQuery->execute($values);

set_flash(
    'success',
    $plantId
    ? 'Plant updated successfully.'
    : 'Plant added successfully.'
);

redirect('/uni/ecosprout/staff/plants.php');
