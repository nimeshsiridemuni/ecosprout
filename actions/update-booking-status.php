<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/staff/bookings.php');
}

verify_csrf();

$bookingId = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
$bookingStatus = $_POST['booking_status'] ?? '';
$allowedStatuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];

if (!$bookingId || !in_array($bookingStatus, $allowedStatuses, true)) {
    set_flash('error', 'Invalid booking update.');
    redirect('/uni/ecosprout/staff/bookings.php');
}

$query = $pdo->prepare(
    'UPDATE service_bookings
     SET booking_status = :booking_status
     WHERE booking_id = :booking_id'
);
$query->execute([
    'booking_status' => $bookingStatus,
    'booking_id' => $bookingId,
]);

set_flash('success', 'Booking status saved successfully.');

redirect('/uni/ecosprout/staff/bookings.php');
