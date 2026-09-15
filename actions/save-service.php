<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_role(['Staff', 'Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/staff/services.php');
}
verify_csrf();

$serviceId = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
$serviceName = trim($_POST['service_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$basePrice = filter_input(INPUT_POST, 'base_price', FILTER_VALIDATE_FLOAT);
$duration = filter_input(INPUT_POST, 'duration_minutes', FILTER_VALIDATE_INT);
$status = $_POST['service_status'] ?? '';

if (
    $serviceName === '' || strlen($serviceName) > 100
    || $basePrice === null || $basePrice === false || $basePrice < 0
    || $duration === null || $duration === false || $duration < 1
    || !in_array($status, ['Available', 'Unavailable'], true)
) {
    set_flash('error', 'Please enter valid service information.');
    redirect('/uni/ecosprout/staff/services.php');
}

$values = [
    'service_name' => $serviceName,
    'description' => $description ?: null,
    'base_price' => $basePrice,
    'duration_minutes' => $duration,
    'service_status' => $status,
];

if ($serviceId) {
    $values['service_id'] = $serviceId;
    $query = $pdo->prepare(
        'UPDATE services SET service_name = :service_name,
         description = :description, base_price = :base_price,
         duration_minutes = :duration_minutes,
         service_status = :service_status
         WHERE service_id = :service_id'
    );
} else {
    $query = $pdo->prepare(
        'INSERT INTO services
         (service_name, description, base_price, duration_minutes, service_status)
         VALUES (:service_name, :description, :base_price, :duration_minutes, :service_status)'
    );
}

$query->execute($values);
set_flash('success', $serviceId ? 'Service updated.' : 'Service created.');
redirect('/uni/ecosprout/staff/services.php');
