<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_role(['Staff', 'Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/staff/workshops.php');
}
verify_csrf();

$workshopId = filter_input(INPUT_POST, 'workshop_id', FILTER_VALIDATE_INT);
$title = trim($_POST['workshop_title'] ?? '');
$description = trim($_POST['description'] ?? '');
$workshopDate = trim($_POST['workshop_date'] ?? '');
$startTime = trim($_POST['start_time'] ?? '');
$location = trim($_POST['location'] ?? '');
$capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
$fee = filter_input(INPUT_POST, 'registration_fee', FILTER_VALIDATE_FLOAT);
$status = $_POST['workshop_status'] ?? '';
$date = DateTime::createFromFormat('!Y-m-d', $workshopDate);

if (
    $title === '' || strlen($title) > 150
    || !$date || $date->format('Y-m-d') !== $workshopDate
    || !preg_match('/^\d{2}:\d{2}$/', $startTime)
    || $location === '' || strlen($location) > 200
    || $capacity === null || $capacity === false || $capacity < 1
    || $fee === null || $fee === false || $fee < 0
    || !in_array($status, ['Scheduled', 'Completed', 'Cancelled'], true)
) {
    set_flash('error', 'Please enter valid workshop information.');
    redirect('/uni/ecosprout/staff/workshops.php');
}

$values = [
    'workshop_title' => $title,
    'description' => $description ?: null,
    'workshop_date' => $workshopDate,
    'start_time' => $startTime,
    'location' => $location,
    'capacity' => $capacity,
    'registration_fee' => $fee,
    'workshop_status' => $status,
];

if ($workshopId) {
    $values['workshop_id'] = $workshopId;
    $query = $pdo->prepare(
        'UPDATE workshops SET workshop_title = :workshop_title,
         description = :description, workshop_date = :workshop_date,
         start_time = :start_time, location = :location, capacity = :capacity,
         registration_fee = :registration_fee, workshop_status = :workshop_status
         WHERE workshop_id = :workshop_id'
    );
} else {
    $query = $pdo->prepare(
        'INSERT INTO workshops
         (workshop_title, description, workshop_date, start_time, location,
          capacity, registration_fee, workshop_status)
         VALUES (:workshop_title, :description, :workshop_date, :start_time,
          :location, :capacity, :registration_fee, :workshop_status)'
    );
}

$query->execute($values);
set_flash('success', $workshopId ? 'Workshop updated.' : 'Workshop created.');
redirect('/uni/ecosprout/staff/workshops.php');
