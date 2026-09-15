<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role([
    'Staff',
    'Administrator',
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Staff Dashboard | EcoSprout</title>
</head>

<body>
    <main>
        <h1>Staff Dashboard</h1>

        <p>
            Welcome,
            <?= escape($_SESSION['full_name']) ?>
        </p>

        <p>You have permission to access staff functions.</p>

        <a href="/uni/ecosprout/index.php">
            Return to homepage
        </a>

        <form
            action="/uni/ecosprout/logout.php"
            method="post"
        >
            <?= csrf_field() ?>

            <button type="submit">
                Logout
            </button>
        </form>
    </main>
</body>
</html>