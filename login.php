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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | EcoSprout</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <main class="auth-page">
        <section class="auth-visual">
            <a href="index.php" class="logo auth-logo">
                <img src="logo.jpeg" alt="EcoSprout logo" class="logo-image">
                EcoSprout
            </a>
            <div class="auth-visual-copy">
                <p class="eyebrow">Welcome back</p>
                <h1>Keep growing what matters.</h1>
                <p>Return to your plants, bookings, orders and nursery support in one calm workspace.</p>
            </div>
            <p class="auth-location">EcoSprout Nursery / Kegalle, Sri Lanka</p>
        </section>

        <section class="auth-panel">
            <div class="auth-form-wrap">
                <a href="index.php" class="auth-back">&larr; Back to EcoSprout</a>
                <p class="eyebrow">Customer access</p>
                <h2>Sign in to your account</h2>
                <p class="auth-subtitle">Manage your plants, orders and gardening plans.</p>

                <?php if ($flash !== null): ?>
                    <div class="<?= escape($flash['type']) ?>" role="alert">
                        <?= escape($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <form action="/uni/ecosprout/actions/login-action.php" method="post" class="auth-form">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control" maxlength="150"
                            value="<?= escape($oldEmail) ?>" autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            autocomplete="current-password" required>
                    </div>

                    <button type="submit" class="btn btn-primary auth-submit">Sign in</button>
                </form>

                <p class="auth-switch">New to EcoSprout? <a href="/uni/ecosprout/register.php">Create an account</a></p>
            </div>
        </section>
    </main>
</body>

</html>