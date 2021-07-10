<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Util\Environment;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Query\Builder;

class EloquentBootstrap
{
    /** @var Manager */
    private $capsule;

    
    public function __construct()
    {
        $database_url = Environment::get('DATABASE_URL');
        $database_config = parse_url($database_url);

        $database_config['scheme'] =
            $this->adjustDriverIfIsPostgres($database_config);

        $configuration = [
            'driver' => $database_config['scheme'],
            'host' => $database_config['host'],
            'port' => $database_config['port'],
            'database' => ltrim($database_config['path'], '/'),
            'username' => $database_config['user'],
            'password' => $database_config['pass'],
            'charset' => 'utf8',
        ];

        $this->capsule = new Manager();
        $this->capsule->addConnection($configuration);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    public function get(string $tableName): Builder
    {
        return $this->capsule->table($tableName);
    }

    private function adjustDriverIfIsPostgres(
        array $database_config
    ): string {
        if ($database_config['scheme'] === 'postgres') {
            return 'pgsql';
        }

        return $database_config['scheme'];
    }
}
