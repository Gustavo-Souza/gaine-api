<?php

declare(strict_types=1);

use App\Util\Environment;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// Setup configuration from environment
Dotenv::createImmutable(__DIR__, '.env')->safeLoad();

$database_url = Environment::get('DATABASE_URL');
$database_config = parse_url($database_url);
$isDebugMode = Environment::get('APP_DEBUG');
$defaultEnvironment = $isDebugMode ? 'local' : 'production';

// Configuration
return [
    // Directories
    'migration_dirs' => [
        'user' => __DIR__ . '/migrations/user',
        'auth' => __DIR__ . '/migrations/auth'
    ],

    // Environments
    'environments' => [
        'local' => [
            'adapter' => $database_config['scheme'],
            'host' => $database_config['host'],
            'port' => $database_config['port'],
            'username' => $database_config['user'],
            'password' => $database_config['pass'],
            'db_name' => ltrim($database_config['path'], '/'),
            'charset' => 'utf8',
        ],
        'production' => [
            'adapter' => $database_config['scheme'],
            'host' => $database_config['host'],
            'port' => $database_config['port'],
            'username' => $database_config['user'],
            'password' => $database_config['pass'],
            'db_name' => ltrim($database_config['path'], '/'),
            'charset' => 'utf8',
        ],
    ],

    // Settings
    'default_environment' => $defaultEnvironment,
    'log_table_name' => 'log',
];
