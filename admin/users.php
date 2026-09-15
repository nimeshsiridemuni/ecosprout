<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Administrator']);

$flash = get_flash();

$userQuery = $pdo->query(
    'SELECT
        u.user_id,
        u.full_name,
        u.email,
        u.account_status,
        u.created_at,
        r.role_name
     FROM users AS u
     INNER JOIN roles AS r
        ON r.role_id = u.role_id
     ORDER BY u.created_at DESC'
);

$users = $userQuery->fetchAll();

$roleQuery = $pdo->query(
    'SELECT role_id, role_name
     FROM roles
     ORDER BY role_name'
);

$roles = $roleQuery->fetchAll();

$accountStatuses = [
    'Active',
    'Inactive',
    'Suspended',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Management | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="dashboard.php">&larr; Admin dashboard</a>
        </p>

        <h1>User and Staff Management</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <section class="card mb-20">
            <h2>Create a staff account</h2>

            <form
                action="../actions/create-staff.php"
                method="post"
            >
                <?= csrf_field() ?>

                <label for="full_name">Full name</label>

                <input
                    id="full_name"
                    name="full_name"
                    type="text"
                    class="form-control"
                    maxlength="100"
                    required
                >

                <label for="email">Email address</label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-control"
                    maxlength="150"
                    required
                >

                <label for="password">Temporary password</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control"
                    minlength="8"
                    maxlength="72"
                    required
                >

                <button type="submit" class="btn btn-primary">
                    Create staff account
                </button>
            </form>
        </section>

        <?php foreach ($users as $user): ?>
            <article class="card mb-20">
                <h2><?= escape($user['full_name']) ?></h2>

                <p><?= escape($user['email']) ?></p>

                <p>
                    Joined:
                    <?= escape($user['created_at']) ?>
                </p>

                <form
                    action="../actions/update-user.php"
                    method="post"
                >
                    <?= csrf_field() ?>

                    <input
                        type="hidden"
                        name="user_id"
                        value="<?= (int) $user['user_id'] ?>"
                    >

                    <label>
                        Role

                        <select
                            name="role_id"
                            class="form-control"
                            required
                        >
                            <?php foreach ($roles as $role): ?>
                                <option
                                    value="<?= (int) $role['role_id'] ?>"
                                    <?= $user['role_name']
                                        === $role['role_name']
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= escape($role['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>
                        Account status

                        <select
                            name="account_status"
                            class="form-control"
                            required
                        >
                            <?php foreach (
                                $accountStatuses as $status
                            ): ?>
                                <option
                                    value="<?= $status ?>"
                                    <?= $user['account_status']
                                        === $status
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= $status ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <button type="submit" class="btn btn-primary">
                        Save changes
                    </button>
                </form>
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>