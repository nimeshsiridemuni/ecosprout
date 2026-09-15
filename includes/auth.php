<?php

declare(strict_types=1);

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash(
            'error',
            'Please log in to access that page.'
        );

        redirect('/uni/ecosprout/login.php');
    }
}

function require_role(array $allowedRoles): void
{
    require_login();

    $currentRole = $_SESSION['role'] ?? '';

    if (!in_array($currentRole, $allowedRoles, true)) {
        http_response_code(403);

        exit(
            'Access denied. You do not have permission ' .
            'to view this page.'
        );
    }
}