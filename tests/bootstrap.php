<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) === 'test') {
    $testDatabasePath = dirname(__DIR__) . '/var/test.db';

    if (!is_dir(dirname($testDatabasePath))) {
        mkdir(dirname($testDatabasePath), 0777, true);
    }

    $_ENV['DATABASE_URL'] = $_SERVER['DATABASE_URL'] = 'sqlite:///' . $testDatabasePath;
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
