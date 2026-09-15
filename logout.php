<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!request_is_post()) {
    redirect('/uni/ecosprout/index.php');
}

verify_csrf();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $cookieDetails = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookieDetails['path'],
        $cookieDetails['domain'],
        $cookieDetails['secure'],
        $cookieDetails['httponly']
    );
}

session_destroy();

redirect('/uni/ecosprout/index.php');