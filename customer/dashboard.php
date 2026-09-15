<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

$flash = get_flash();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Customer Dashboard | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p><a href="../index.php">&larr; Home</a></p>

        <h1>
            Welcome,
            <?= escape($_SESSION['full_name']) ?>
        </h1>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <section class="grid-4">
            <article class="card">
                <h2>Plants</h2>
                <p>Browse plants and add them to your cart.</p>

                <a href="../plants.php" class="btn btn-primary">
                    Browse plants
                </a>
            </article>

            <article class="card">
                <h2>My Orders</h2>
                <p>Review your previous nursery orders.</p>

                <a href="orders.php" class="btn btn-primary">
                    View orders
                </a>
            </article>

            <article class="card">
                <h2>My Bookings</h2>
                <p>Review gardening-service bookings.</p>

                <a href="bookings.php" class="btn btn-primary">
                    View bookings
                </a>
            </article>

            <article class="card">
                <h2>My Workshops</h2>
                <p>Review your workshop registrations.</p>

                <a href="workshops.php" class="btn btn-primary">
                    View workshops
                </a>
            </article>

            <article class="card">
                <h2>My Inquiries</h2>
                <p>Read responses from the nursery team.</p>

                <a href="inquiries.php" class="btn btn-primary">
                    View inquiries
                </a>
            </article>
        </section>

        <form action="../logout.php" method="post" class="mt-20">
            <?= csrf_field() ?>

            <button type="submit" class="btn btn-outline">
                Logout
            </button>
        </form>
    </main>
</body>
</html>
