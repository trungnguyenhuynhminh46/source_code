<?php

declare(strict_types = 1);

namespace App;

/**
 * @property-read ?array  $db
 * @property-read ?string $environment
 */
class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'db'          => [
                'driver'   => $env['DB_DRIVER'] ?? 'pdo_mysql',
                'host'     => $env['DB_HOST'],
                'port'     => $env['DB_PORT'] ?? 3306,
                'dbname'   => $env['DB_DATABASE'],
                'user'     => $env['DB_USER'],
                'password' => $env['DB_PASS'],
            ],
            'environment' => $env['APP_ENVIRONMENT'] ?? 'production',
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}
