<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (isset($_SESSION['user_id'])) {
    redirect('/uni/ecosprout/index.php');
}

$flash = get_flash();
$oldEmail = $_SESSION['old_login_email'] ?? '';
unset($_SESSION['old_login_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | EcoSprout</title>
</head>

<body>
    <main>
        <h1>Login to EcoSprout</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <form
            action="/uni/ecosprout/actions/login-action.php"
            method="post"
        >
            <?= csrf_field() ?>

            <div>
                <label for="email">Email address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="150"
                    value="<?= escape($oldEmail) ?>"
                    required
                >
            </div>

            <div>
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <button type="submit">
                Login
            </button>
        </form>

        <p>
            Do not have an account?
            <a href="/uni/ecosprout/register.php">
                Register
            </a>
        </p>
    </main>
</body>
</html>