<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$databaseQuery = $pdo->query('SELECT DATABASE()');
$databaseName = $databaseQuery->fetchColumn();

$securityHelpersReady = strlen(csrf_token()) === 64;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>EcoSprout Setup Test</title>
</head>

<body>
    <main>
        <h1>EcoSprout setup completed</h1>

        <p>
            PHP version:
            <?= escape(PHP_VERSION) ?>
        </p>

        <p>
            Connected database:
            <?= escape((string) $databaseName) ?>
        </p>

        <p>
            Security helpers:
            <?= $securityHelpersReady ? 'Ready' : 'Not ready' ?>
        </p>
    </main>
</body>
</html>