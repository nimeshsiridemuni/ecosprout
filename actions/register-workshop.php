<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

require_role(['Customer']);

if (!request_is_post()) {
    redirect('/uni/ecosprout/workshops.php');
}

verify_csrf();

$workshopId = filter_input(
    INPUT_POST,
    'workshop_id',
    FILTER_VALIDATE_INT
);

if (!$workshopId) {
    set_flash('error', 'Invalid workshop selection.');
    redirect('/uni/ecosprout/workshops.php');
}

try {
    $pdo->beginTransaction();

    $workshopQuery = $pdo->prepare(
        'SELECT
            workshop_id,
            capacity
         FROM workshops
         WHERE workshop_id = :workshop_id
           AND workshop_status = \'Scheduled\'
           AND workshop_date >= CURRENT_DATE
         FOR UPDATE'
    );

    $workshopQuery->execute([
        'workshop_id' => $workshopId,
    ]);

    $workshop = $workshopQuery->fetch();

    if (!$workshop) {
        throw new RuntimeException(
            'The workshop is no longer available.'
        );
    }

    $duplicateQuery = $pdo->prepare(
        'SELECT registration_id
         FROM workshop_registrations
         WHERE workshop_id = :workshop_id
           AND customer_id = :customer_id
         LIMIT 1'
    );

    $duplicateQuery->execute([
        'workshop_id' => $workshopId,
        'customer_id' => (int) $_SESSION['user_id'],
    ]);

    if ($duplicateQuery->fetch()) {
        throw new RuntimeException(
            'You have already registered for this workshop.'
        );
    }

    $countQuery = $pdo->prepare(
        'SELECT COUNT(*)
         FROM workshop_registrations
         WHERE workshop_id = :workshop_id
           AND registration_status = \'Registered\''
    );

    $countQuery->execute([
        'workshop_id' => $workshopId,
    ]);

    $registeredCount = (int) $countQuery->fetchColumn();

    if ($registeredCount >= (int) $workshop['capacity']) {
        throw new RuntimeException(
            'The workshop has reached its capacity.'
        );
    }

    $registrationQuery = $pdo->prepare(
        'INSERT INTO workshop_registrations (
            workshop_id,
            customer_id,
            registration_status
         ) VALUES (
            :workshop_id,
            :customer_id,
            \'Registered\'
         )'
    );

    $registrationQuery->execute([
        'workshop_id' => $workshopId,
        'customer_id' => (int) $_SESSION['user_id'],
    ]);

    $pdo->commit();

    set_flash(
        'success',
        'You registered for the workshop successfully.'
    );

    redirect('/uni/ecosprout/customer/workshops.php');
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if (
        !$exception instanceof RuntimeException
        && $exception instanceof PDOException
        && $exception->getCode() === '23000'
    ) {
        $message =
            'You have already registered for this workshop.';
    } elseif ($exception instanceof RuntimeException) {
        $message = $exception->getMessage();
    } else {
        error_log($exception->getMessage());
        $message =
            'Registration could not be completed.';
    }

    set_flash('error', $message);

    redirect('/uni/ecosprout/workshops.php');
}
