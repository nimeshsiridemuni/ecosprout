<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$databaseQuery = $pdo->query('SELECT DATABASE()');
$databaseName = $databaseQuery->fetchColumn();
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
            <?= htmlspecialchars(PHP_VERSION) ?>
        </p>

        <p>
            Connected database:
            <?= htmlspecialchars((string) $databaseName) ?>
        </p>
    </main>
</body>
</html>