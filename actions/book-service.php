<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/services.php');
}

verify_csrf();

$serviceId = filter_input(
    INPUT_POST,
    'service_id',
    FILTER_VALIDATE_INT
);

$bookingDate = trim($_POST['booking_date'] ?? '');
$bookingTime = trim($_POST['booking_time'] ?? '');
$serviceAddress = trim($_POST['service_address'] ?? '');
$customerNotes = trim($_POST['customer_notes'] ?? '');

$date = DateTime::createFromFormat(
    '!Y-m-d',
    $bookingDate
);

$dateIsValid =
    $date !== false
    && $date->format('Y-m-d') === $bookingDate;

$minimumDate = new DateTime('tomorrow midnight');

if (
    !$serviceId
    || !$dateIsValid
    || $date < $minimumDate
    || !preg_match('/^\d{2}:\d{2}$/', $bookingTime)
    || $serviceAddress === ''
    || strlen($serviceAddress) > 255
    || strlen($customerNotes) > 1000
) {
    set_flash(
        'error',
        'Please enter valid booking information.'
    );

    redirect('/uni/ecosprout/services.php');
}

$serviceQuery = $pdo->prepare(
    'SELECT service_id
     FROM services
     WHERE service_id = :service_id
       AND service_status = \'Available\'
     LIMIT 1'
);

$serviceQuery->execute([
    'service_id' => $serviceId,
]);

if (!$serviceQuery->fetch()) {
    set_flash(
        'error',
        'The selected service is unavailable.'
    );

    redirect('/uni/ecosprout/services.php');
}

$bookingQuery = $pdo->prepare(
    'INSERT INTO service_bookings (
        customer_id,
        service_id,
        booking_date,
        booking_time,
        service_address,
        customer_notes,
        booking_status
     ) VALUES (
        :customer_id,
        :service_id,
        :booking_date,
        :booking_time,
        :service_address,
        :customer_notes,
        \'Pending\'
     )'
);

$bookingQuery->execute([
    'customer_id' => (int) $_SESSION['user_id'],
    'service_id' => $serviceId,
    'booking_date' => $bookingDate,
    'booking_time' => $bookingTime,
    'service_address' => $serviceAddress,
    'customer_notes' => $customerNotes !== ''
        ? $customerNotes
        : null,
]);

set_flash(
    'success',
    'Your service booking was submitted successfully.'
);

redirect('/uni/ecosprout/customer/bookings.php');
