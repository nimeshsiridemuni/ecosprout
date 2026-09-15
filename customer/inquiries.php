<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

$inquiryQuery = $pdo->prepare(
    'SELECT
        subject,
        message,
        staff_response,
        inquiry_status,
        created_at,
        responded_at
     FROM inquiries
     WHERE customer_id = :customer_id
     ORDER BY created_at DESC'
);

$inquiryQuery->execute([
    'customer_id' => (int) $_SESSION['user_id'],
]);

$inquiries = $inquiryQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Inquiries | EcoSprout</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <main class="container mt-40">
        <p><a href="dashboard.php">&larr; Dashboard</a></p>
        <h1>My Inquiries</h1>

        <?php if ($inquiries === []): ?>
            <section class="card">
                <p>You have not submitted an inquiry.</p>
                <a href="../contact.php" class="btn btn-primary">
                    Ask EcoSprout
                </a>
            </section>
        <?php else: ?>
            <?php foreach ($inquiries as $inquiry): ?>
                <article class="card mb-20">
                    <h2><?= escape($inquiry['subject']) ?></h2>
                    <p><strong>Status:</strong> <?= escape($inquiry['inquiry_status']) ?></p>
                    <p><strong>Your message:</strong></p>
                    <p><?= nl2br(escape($inquiry['message'])) ?></p>

                    <?php if (!empty($inquiry['staff_response'])): ?>
                        <p><strong>EcoSprout response:</strong></p>
                        <p><?= nl2br(escape($inquiry['staff_response'])) ?></p>
                    <?php else: ?>
                        <p class="text-secondary">Waiting for a staff response.</p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
