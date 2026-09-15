<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$flash = get_flash();

$customerName = $_SESSION['full_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Contact Us | EcoSprout</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <header class="public-header">
        <div class="container header-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">🌿</span>
                EcoSprout
            </a>

            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="plants.php">Plants</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="workshops.php">Workshops</a></li>

                    <li>
                        <a href="contact.php" class="active">
                            Contact
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mt-40">
        <h1>Contact EcoSprout</h1>

        <p class="text-secondary">
            Ask us about plants, gardening services or workshops.
        </p>

        <?php if ($flash !== null): ?>
            <div class="<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>

        <section class="card">
            <form
                action="actions/submit-inquiry.php"
                method="post"
            >
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="customer_name">
                        Your name
                    </label>

                    <input
                        id="customer_name"
                        name="customer_name"
                        type="text"
                        class="form-control"
                        value="<?= escape($customerName) ?>"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="customer_email">
                        Email address
                    </label>

                    <input
                        id="customer_email"
                        name="customer_email"
                        type="email"
                        class="form-control"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="subject">
                        Subject
                    </label>

                    <input
                        id="subject"
                        name="subject"
                        type="text"
                        class="form-control"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="message">
                        Message
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        class="form-control"
                        maxlength="2000"
                        required
                    ></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit inquiry
                </button>
            </form>
        </section>

        <section class="card mt-20">
            <h2>Visit the nursery</h2>

            <p>Kegalle, Sri Lanka</p>
            <p>Email: info@ecosprout.lk</p>
        </section>
    </main>
</body>
</html>