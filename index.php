<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();
$isLoggedIn = isset($_SESSION['user_id']);

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

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

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

        <?php if ($isLoggedIn): ?>
            <h2>
                Welcome,
                <?= escape($_SESSION['full_name']) ?>
            </h2>

            <p>
                Account role:
                <?= escape($_SESSION['role']) ?>
            </p>

            <form
                action="/uni/ecosprout/logout.php"
                method="post"
            >
                <?= csrf_field() ?>

                <button type="submit">
                    Logout
                </button>
            </form>
        <?php else: ?>
            <p>You are not currently logged in.</p>

            <p>
                <a href="/uni/ecosprout/login.php">
                    Login
                </a>

                or

                <a href="/uni/ecosprout/register.php">
                    create an account
                </a>
            </p>
        <?php endif; ?>
        <p>
    <a href="/uni/ecosprout/plants.php">
        Browse available plants
    </a>
</p>
    </main>
</body>
</html>