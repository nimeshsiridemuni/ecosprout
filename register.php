<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();

$oldInput = $_SESSION['old_registration'] ?? [];
unset($_SESSION['old_registration']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | EcoSprout</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <main class="auth-page">
        <section class="auth-visual auth-visual-register">
            <a href="index.php" class="logo auth-logo">
                <img src="logo.jpeg" alt="EcoSprout logo" class="logo-image">
                EcoSprout
            </a>
            <div class="auth-visual-copy">
                <p class="eyebrow">A greener routine starts here</p>
                <h1>Make room for more life.</h1>
                <p>Save your details, follow your orders and get useful care support from the EcoSprout team.</p>
            </div>
            <p class="auth-location">Plants / services / workshops / support</p>
        </section>

        <section class="auth-panel">
            <div class="auth-form-wrap auth-form-wrap-register">
                <a href="index.php" class="auth-back">&larr; Back to EcoSprout</a>
                <p class="eyebrow">Join the nursery</p>
                <h2>Create your account</h2>
                <p class="auth-subtitle">A few details and you are ready to grow with us.</p>

                <?php if ($flash !== null): ?>
                    <div class="<?= escape($flash['type']) ?>" role="alert">
                        <?= escape($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <form action="/uni/ecosprout/actions/register-action.php" method="post" class="auth-form">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="full_name">Full name</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" maxlength="100"
                            value="<?= escape($oldInput['full_name'] ?? '') ?>" autocomplete="name" required>
                    </div>

                    <div class="auth-form-row">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" id="email" name="email" class="form-control" maxlength="150"
                                value="<?= escape($oldInput['email'] ?? '') ?>" autocomplete="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telephone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" maxlength="20"
                                placeholder="07X XXX XXXX" value="<?= escape($oldInput['phone'] ?? '') ?>"
                                autocomplete="tel">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" class="form-control" maxlength="255" rows="3"
                            autocomplete="street-address"><?= escape($oldInput['address'] ?? '') ?></textarea>
                    </div>

                    <div class="auth-form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" minlength="8"
                                maxlength="72" autocomplete="new-password" required>
                            <small>At least eight characters.</small>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                minlength="8" maxlength="72" autocomplete="new-password" required>
                        </div>
                    </div>

                    <label class="auth-check">
                        <input type="checkbox" id="accept_terms" name="accept_terms" value="1" required>
                        <span>I agree with the terms and conditions.</span>
                    </label>

                    <button type="submit" class="btn btn-primary auth-submit">Create account</button>
                </form>

                <p class="auth-switch">Already have an account? <a href="/uni/ecosprout/login.php">Sign in</a></p>
            </div>
        </section>
    </main>
</body>

</html>
