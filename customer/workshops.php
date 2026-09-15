<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

$flash = get_flash();

$registrationQuery = $pdo->prepare(
    'SELECT
        wr.registration_id,
        wr.registered_at,
        wr.registration_status,
        w.workshop_title,
        w.workshop_date,
        w.start_time,
        w.location,
        w.registration_fee
     FROM workshop_registrations AS wr
     INNER JOIN workshops AS w
        ON w.workshop_id = wr.workshop_id
     WHERE wr.customer_id = :customer_id
     ORDER BY w.workshop_date DESC'
);

$registrationQuery->execute([
    'customer_id' => (int) $_SESSION['user_id'],
]);

$registrations = $registrationQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Workshops | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="../workshops.php">
                &larr; View workshops
            </a>
        </p>

        <h1>My Workshop Registrations</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($registrations === []): ?>
            <section class="card">
                <p>
                    You have not registered for a workshop.
                </p>
            </section>
        <?php else: ?>
            <?php foreach ($registrations as $registration): ?>
                <article class="card mb-20">
                    <h2>
                        <?= escape(
                            $registration['workshop_title']
                        ) ?>
                    </h2>

                    <p>
                        Date:
                        <?= escape(
                            $registration['workshop_date']
                        ) ?>
                    </p>

                    <p>
                        Time:
                        <?= escape(
                            $registration['start_time']
                        ) ?>
                    </p>

                    <p>
                        Location:
                        <?= escape(
                            $registration['location']
                        ) ?>
                    </p>

                    <p>
                        Fee:
                        LKR
                        <?= number_format(
                            (float) $registration[
                                'registration_fee'
                            ],
                            2
                        ) ?>
                    </p>

                    <p>
                        Status:
                        <strong>
                            <?= escape(
                                $registration[
                                    'registration_status'
                                ]
                            ) ?>
                        </strong>
                    </p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>