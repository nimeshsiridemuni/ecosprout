<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);

$flash = get_flash();

$inquiryQuery = $pdo->query(
    'SELECT
        inquiry_id,
        customer_name,
        customer_email,
        subject,
        message,
        staff_response,
        inquiry_status,
        created_at,
        responded_at
     FROM inquiries
     ORDER BY
        CASE inquiry_status
            WHEN \'New\' THEN 1
            WHEN \'In Progress\' THEN 2
            WHEN \'Responded\' THEN 3
            ELSE 4
        END,
        created_at DESC'
);

$inquiries = $inquiryQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Inquiries | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p>
            <a href="dashboard.php">
                &larr; Staff dashboard
            </a>
        </p>

        <h1>Customer Inquiries</h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($inquiries === []): ?>
            <section class="card">
                <p>No inquiries have been submitted.</p>
            </section>
        <?php else: ?>
            <?php foreach ($inquiries as $inquiry): ?>
                <article class="card mb-20">
                    <h2>
                        <?= escape($inquiry['subject']) ?>
                    </h2>

                    <p>
                        <strong>From:</strong>
                        <?= escape($inquiry['customer_name']) ?>
                    </p>

                    <p>
                        <strong>Email:</strong>
                        <?= escape($inquiry['customer_email']) ?>
                    </p>

                    <p>
                        <strong>Submitted:</strong>
                        <?= escape($inquiry['created_at']) ?>
                    </p>

                    <p>
                        <strong>Status:</strong>
                        <?= escape($inquiry['inquiry_status']) ?>
                    </p>

                    <h3>Customer message</h3>

                    <p>
                        <?= nl2br(escape($inquiry['message'])) ?>
                    </p>

                    <?php if (
                        !empty($inquiry['staff_response'])
                    ): ?>
                        <h3>Current response</h3>

                        <p>
                            <?= nl2br(
                                escape($inquiry['staff_response'])
                            ) ?>
                        </p>
                    <?php endif; ?>

                    <form
                        action="../actions/respond-inquiry.php"
                        method="post"
                    >
                        <?= csrf_field() ?>

                        <input
                            type="hidden"
                            name="inquiry_id"
                            value="<?= (int) $inquiry['inquiry_id'] ?>"
                        >

                        <label for="response-<?= (int) $inquiry['inquiry_id'] ?>">
                            Staff response
                        </label>

                        <textarea
                            id="response-<?= (int) $inquiry['inquiry_id'] ?>"
                            name="staff_response"
                            class="form-control"
                            maxlength="2000"
                            required
                        ><?= escape($inquiry['staff_response'] ?? '') ?></textarea>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Save response
                        </button>
                    </form>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>