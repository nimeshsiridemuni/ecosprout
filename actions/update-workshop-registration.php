<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_role(['Staff', 'Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/staff/workshop-registrations.php');
}
verify_csrf();

$registrationId = filter_input(
    INPUT_POST,
    'registration_id',
    FILTER_VALIDATE_INT
);
$status = $_POST['registration_status'] ?? '';

if (!$registrationId || !in_array($status, ['Registered', 'Attended', 'Cancelled'], true)) {
    set_flash('error', 'Invalid workshop registration update.');
    redirect('/uni/ecosprout/staff/workshop-registrations.php');
}

$query = $pdo->prepare(
    'UPDATE workshop_registrations
     SET registration_status = :registration_status
     WHERE registration_id = :registration_id'
);
$query->execute([
    'registration_status' => $status,
    'registration_id' => $registrationId,
]);

set_flash('success', 'Workshop registration status saved.');
redirect('/uni/ecosprout/staff/workshop-registrations.php');
