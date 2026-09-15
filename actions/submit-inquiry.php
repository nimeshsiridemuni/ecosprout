<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!request_is_post()) {
    redirect('/uni/ecosprout/contact.php');
}

verify_csrf();

$customerName = trim($_POST['customer_name'] ?? '');
$customerEmail = strtolower(
    trim($_POST['customer_email'] ?? '')
);
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (
    $customerName === ''
    || strlen($customerName) > 100
    || !filter_var(
        $customerEmail,
        FILTER_VALIDATE_EMAIL
    )
    || strlen($customerEmail) > 150
    || $subject === ''
    || strlen($subject) > 150
    || $message === ''
    || strlen($message) > 2000
) {
    set_flash(
        'error',
        'Please complete every field using valid information.'
    );

    redirect('/uni/ecosprout/contact.php');
}

$customerId = isset($_SESSION['user_id'])
    ? (int) $_SESSION['user_id']
    : null;

$inquiryQuery = $pdo->prepare(
    'INSERT INTO inquiries (
        customer_id,
        customer_name,
        customer_email,
        subject,
        message,
        inquiry_status
     ) VALUES (
        :customer_id,
        :customer_name,
        :customer_email,
        :subject,
        :message,
        \'New\'
     )'
);

$inquiryQuery->execute([
    'customer_id' => $customerId,
    'customer_name' => $customerName,
    'customer_email' => $customerEmail,
    'subject' => $subject,
    'message' => $message,
]);

set_flash(
    'success',
    'Your inquiry was submitted successfully.'
);

redirect('/uni/ecosprout/contact.php');