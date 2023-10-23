<?php

declare(strict_types = 1);

use App\Config;

use Doctrine\DBAL\DriverManager;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\ORMSetup;

use Slim\Views\Twig;

use Twig\Extra\Intl\IntlExtension;

use function DI\create;

return [
    Config::class        => create(Config::class)->constructor($_ENV),
    EntityManager::class => function(Config $config) {
        $ormConfig = ORMSetup::createXMLMetadataConfiguration(paths:[__DIR__ . '/../app/Entity'], isDevMode: $config->environment === 'development');

        return new EntityManager(
            DriverManager::getConnection($config->db, $ormConfig),
            $ormConfig
        );
    },
    Twig::class          => function (Config $config) {
        $twig = Twig::create(VIEW_PATH, [
            'cache'       => STORAGE_PATH . '/cache',
            'auto_reload' => $config->environment === 'development',
        ]);

        $twig->addExtension(new IntlExtension());

        return $twig;
    },
];
