<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/staff/inquiries.php');
}

verify_csrf();

$inquiryId = filter_input(
    INPUT_POST,
    'inquiry_id',
    FILTER_VALIDATE_INT
);

$staffResponse = trim(
    $_POST['staff_response'] ?? ''
);

if (
    !$inquiryId
    || $staffResponse === ''
    || strlen($staffResponse) > 2000
) {
    set_flash(
        'error',
        'Please enter a valid response.'
    );

    redirect('/uni/ecosprout/staff/inquiries.php');
}

$responseQuery = $pdo->prepare(
    'UPDATE inquiries
     SET
        assigned_staff_id = :staff_id,
        staff_response = :staff_response,
        inquiry_status = \'Responded\',
        responded_at = CURRENT_TIMESTAMP
     WHERE inquiry_id = :inquiry_id'
);

$responseQuery->execute([
    'staff_id' => (int) $_SESSION['user_id'],
    'staff_response' => $staffResponse,
    'inquiry_id' => $inquiryId,
]);

if ($responseQuery->rowCount() !== 1) {
    set_flash(
        'error',
        'The inquiry could not be found or updated.'
    );
} else {
    set_flash(
        'success',
        'The response was saved successfully.'
    );
}

redirect('/uni/ecosprout/staff/inquiries.php');