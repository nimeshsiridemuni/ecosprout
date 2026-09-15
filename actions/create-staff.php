<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/admin/users.php');
}

verify_csrf();

$fullName = trim($_POST['full_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (
    $fullName === ''
    || strlen($fullName) > 100
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || strlen($email) > 150
    || strlen($password) < 8
    || strlen($password) > 72
) {
    set_flash(
        'error',
        'Please enter valid staff account details.'
    );

    redirect('/uni/ecosprout/admin/users.php');
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
        'An account already uses that email address.'
    );

    redirect('/uni/ecosprout/admin/users.php');
}

$roleQuery = $pdo->prepare(
    'SELECT role_id
     FROM roles
     WHERE role_name = \'Staff\'
     LIMIT 1'
);

$roleQuery->execute();
$staffRoleId = $roleQuery->fetchColumn();

if (!$staffRoleId) {
    set_flash('error', 'The Staff role could not be found.');
    redirect('/uni/ecosprout/admin/users.php');
}

$userQuery = $pdo->prepare(
    'INSERT INTO users (
        role_id,
        full_name,
        email,
        password_hash,
        account_status
     ) VALUES (
        :role_id,
        :full_name,
        :email,
        :password_hash,
        \'Active\'
     )'
);

$userQuery->execute([
    'role_id' => (int) $staffRoleId,
    'full_name' => $fullName,
    'email' => $email,
    'password_hash' => password_hash(
        $password,
        PASSWORD_DEFAULT
    ),
]);

set_flash('success', 'Staff account created successfully.');

redirect('/uni/ecosprout/admin/users.php');