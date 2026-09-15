<?php

declare(strict_types=1);

$configFile = __DIR__ . '/local.php';

if (!is_file($configFile)) {
    exit(
        'Database configuration is missing. ' .
        'Copy local.example.php and rename it local.php.'
    );
}

$config = require $configFile;

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=utf8mb4',
    $config['database_host'],
    $config['database_name']
);

try {
    $pdo = new PDO(
        $dsn,
        $config['database_user'],
        $config['database_password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    error_log($exception->getMessage());

    exit(
        'EcoSprout could not connect to the database. ' .
        'Please check the local database configuration.'
    );
}