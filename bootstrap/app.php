<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\App;
use Slim\Factory\AppFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Builds the application. Tests pass $overrides to swap services (e.g. the session).
 *
 * @param array<string, mixed> $overrides
 *
 * @return App<\Psr\Container\ContainerInterface>
 */
return static function (array $overrides = []): App {
    if (is_file(dirname(__DIR__) . '/.env')) {
        Dotenv::createImmutable(dirname(__DIR__))->load();
    }

    $builder = new ContainerBuilder();
    $builder->addDefinitions(require dirname(__DIR__) . '/config/container.php');
    $builder->addDefinitions($overrides);
    $container = $builder->build();

    $app = AppFactory::createFromContainer($container);

    /** @var array{env: string, debug: bool} $settings */
    $settings = $container->get('settings');
    (require dirname(__DIR__) . '/config/middleware.php')($app, $settings['debug'], $settings['env'] !== 'testing');
    (require dirname(__DIR__) . '/config/routes.php')($app);

    return $app;
};
