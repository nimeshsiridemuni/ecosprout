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

if (!$plantId) {
    set_flash('error', 'Invalid plant selection.');
    redirect('/uni/ecosprout/staff/plants.php');
}

$plantQuery = $pdo->prepare(
    'UPDATE plants
     SET plant_status = \'Inactive\'
     WHERE plant_id = :plant_id'
);

$plantQuery->execute([
    'plant_id' => $plantId,
]);

set_flash('success', 'Plant deactivated successfully.');

redirect('/uni/ecosprout/staff/plants.php');