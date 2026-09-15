<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!request_is_post()) {
    redirect('/uni/ecosprout/register.php');
}

verify_csrf();

$fullName = trim($_POST['full_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$acceptedTerms = isset($_POST['accept_terms']);

$_SESSION['old_registration'] = [
    'full_name' => $fullName,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
];

$errors = [];

if ($fullName === '' || strlen($fullName) < 2) {
    $errors[] = 'Please enter your full name.';
}

if (strlen($fullName) > 100) {
    $errors[] = 'The full name is too long.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($phone !== '') {
    $phoneWithoutSpaces = str_replace(
        [' ', '-'],
        '',
        $phone
    );

    if (
        !preg_match(
            '/^(?:\+94|0)7\d{8}$/',
            $phoneWithoutSpaces
        )
    ) {
        $errors[] = 'Please enter a valid Sri Lankan mobile number.';
    } else {
        $phone = $phoneWithoutSpaces;
    }
}

if (strlen($address) > 255) {
    $errors[] = 'The address is too long.';
}

if (strlen($password) < 8) {
    $errors[] = 'The password must contain at least eight characters.';
}

if ($password !== $confirmPassword) {
    $errors[] = 'The passwords do not match.';
}

if (!$acceptedTerms) {
    $errors[] = 'You must accept the website terms.';
}

if ($errors !== []) {
    set_flash('error', implode(' ', $errors));
    redirect('/uni/ecosprout/register.php');
}

$duplicateQuery = $pdo->prepare(
    'SELECT user_id
     FROM users
     WHERE email = :email
     LIMIT 1'
);

$duplicateQuery->execute([
    'email' => $email,
]);

if ($duplicateQuery->fetch()) {
    set_flash(
        'error',
        'An account already exists with this email address.'
    );

    redirect('/uni/ecosprout/register.php');
}

$roleQuery = $pdo->prepare(
    'SELECT role_id
     FROM roles
     WHERE role_name = :role_name
     LIMIT 1'
);

$roleQuery->execute([
    'role_name' => 'Customer',
]);

$customerRoleId = $roleQuery->fetchColumn();

if ($customerRoleId === false) {
    set_flash(
        'error',
        'Registration is temporarily unavailable.'
    );

    redirect('/uni/ecosprout/register.php');
}

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

try {
    $insertQuery = $pdo->prepare(
        'INSERT INTO users (
            role_id,
            full_name,
            email,
            password_hash,
            phone,
            address
        ) VALUES (
            :role_id,
            :full_name,
            :email,
            :password_hash,
            :phone,
            :address
        )'
    );

    $insertQuery->execute([
        'role_id' => $customerRoleId,
        'full_name' => $fullName,
        'email' => $email,
        'password_hash' => $passwordHash,
        'phone' => $phone !== '' ? $phone : null,
        'address' => $address !== '' ? $address : null,
    ]);
} catch (PDOException $exception) {
    error_log($exception->getMessage());

    set_flash(
        'error',
        'The account could not be created. Please try again.'
    );

    redirect('/uni/ecosprout/register.php');
}

unset($_SESSION['old_registration']);

set_flash(
    'success',
    'Your EcoSprout account was created successfully.'
);

redirect('/uni/ecosprout/login.php');