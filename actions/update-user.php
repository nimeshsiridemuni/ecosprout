<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Administrator']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/admin/users.php');
}

verify_csrf();

$userId = filter_input(
    INPUT_POST,
    'user_id',
    FILTER_VALIDATE_INT
);

$roleId = filter_input(
    INPUT_POST,
    'role_id',
    FILTER_VALIDATE_INT
);

$accountStatus = $_POST['account_status'] ?? '';

$allowedStatuses = [
    'Active',
    'Inactive',
    'Suspended',
];

if (
    !$userId
    || !$roleId
    || !in_array(
        $accountStatus,
        $allowedStatuses,
        true
    )
) {
    set_flash('error', 'Invalid user update.');
    redirect('/uni/ecosprout/admin/users.php');
}

if ($userId === (int) $_SESSION['user_id']) {
    set_flash(
        'error',
        'You cannot change your own role or account status.'
    );

    redirect('/uni/ecosprout/admin/users.php');
}

$roleQuery = $pdo->prepare(
    'SELECT role_id
     FROM roles
     WHERE role_id = :role_id'
);

$roleQuery->execute([
    'role_id' => $roleId,
]);

if (!$roleQuery->fetch()) {
    set_flash('error', 'The selected role does not exist.');
    redirect('/uni/ecosprout/admin/users.php');
}

$userQuery = $pdo->prepare(
    'UPDATE users
     SET
        role_id = :role_id,
        account_status = :account_status
     WHERE user_id = :user_id'
);

$userQuery->execute([
    'role_id' => $roleId,
    'account_status' => $accountStatus,
    'user_id' => $userId,
]);

set_flash('success', 'User account updated successfully.');

redirect('/uni/ecosprout/admin/users.php');