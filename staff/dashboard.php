<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Staff', 'Administrator']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Staff Dashboard | EcoSprout</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <main class="container mt-40">
        <p><a href="../index.php">&larr; Home</a></p>

        <h1>Staff Dashboard</h1>

        <p>
            Welcome,
            <?= escape($_SESSION['full_name']) ?>
        </p>

        <section class="grid-4">
            <article class="card">
                <h2>Plant inventory</h2>

                <a href="plants.php" class="btn btn-primary">
                    Manage plants
                </a>
            </article>

            <article class="card">
                <h2>Customer orders</h2>

                <a href="orders.php" class="btn btn-primary">
                    Manage orders
                </a>
            </article>

            <article class="card">
                <h2>Customer inquiries</h2>

                <a href="inquiries.php" class="btn btn-primary">
                    Manage inquiries
                </a>
            </article>

            <article class="card">
                <h2>Service bookings</h2>
                <a href="bookings.php" class="btn btn-primary">
                    Manage bookings
                </a>
            </article>

            <article class="card">
                <h2>Services</h2>
                <a href="services.php" class="btn btn-primary">
                    Manage services
                </a>
            </article>

            <article class="card">
                <h2>Workshops</h2>
                <a href="workshops.php" class="btn btn-primary">
                    Manage workshops
                </a>
            </article>

            <article class="card">
                <h2>Public website</h2>

                <a href="../index.php" class="btn btn-outline">
                    View website
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
