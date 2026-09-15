<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!request_is_post()) {
    redirect('/uni/ecosprout/login.php');
}

verify_csrf();

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

$_SESSION['old_login_email'] = $email;

if (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
    || $password === ''
) {
    set_flash(
        'error',
        'Please enter a valid email address and password.'
    );

    redirect('/uni/ecosprout/login.php');
}

$loginQuery = $pdo->prepare(
    'SELECT
        u.user_id,
        u.full_name,
        u.email,
        u.password_hash,
        u.account_status,
        r.role_name
     FROM users AS u
     INNER JOIN roles AS r
        ON r.role_id = u.role_id
     WHERE u.email = :email
     LIMIT 1'
);

$loginQuery->execute([
    'email' => $email,
]);

$user = $loginQuery->fetch();

if (
    !$user
    || !password_verify($password, $user['password_hash'])
    || $user['account_status'] !== 'Active'
) {
    set_flash(
        'error',
        'The email address or password is incorrect.'
    );

    redirect('/uni/ecosprout/login.php');
}

session_regenerate_id(true);

$_SESSION['user_id'] = (int) $user['user_id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role'] = $user['role_name'];

unset($_SESSION['old_login_email']);

set_flash(
    'success',
    'Welcome back, ' . $user['full_name'] . '!'
);

redirect('/uni/ecosprout/index.php');