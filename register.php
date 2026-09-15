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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register | EcoSprout</title>
</head>

<body>
    <main>
        <h1>Create an EcoSprout account</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <form
            action="/uni/ecosprout/actions/register-action.php"
            method="post"
        >
            <?= csrf_field() ?>

            <div>
                <label for="full_name">Full name</label>

                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    maxlength="100"
                    value="<?= escape($oldInput['full_name'] ?? '') ?>"
                    required
                >
            </div>

            <div>
                <label for="email">Email address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="150"
                    value="<?= escape($oldInput['email'] ?? '') ?>"
                    required
                >
            </div>

            <div>
                <label for="phone">Telephone</label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    maxlength="20"
                    placeholder="07X XXX XXXX"
                    value="<?= escape($oldInput['phone'] ?? '') ?>"
                >
            </div>

            <div>
                <label for="address">Address</label>

                <textarea
                    id="address"
                    name="address"
                    maxlength="255"
                    rows="3"
                ><?= escape($oldInput['address'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required
                >

                <small>Use at least eight characters.</small>
            </div>

            <div>
                <label for="confirm_password">
                    Confirm password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="8"
                    required
                >
            </div>

            <div>
                <input
                    type="checkbox"
                    id="accept_terms"
                    name="accept_terms"
                    value="1"
                    required
                >

                <label for="accept_terms">
                    I agree with the terms and conditions.
                </label>
            </div>

            <button type="submit">
                Create account
            </button>
        </form>
    </main>
</body>
</html>