<?php

declare(strict_types=1);

use App\Database\ConnectionFactory;
use App\Database\Migrator;
use App\Http\Csrf;
use App\Http\Session\NativeSession;
use App\Http\Session\Session;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;
use Twig\TwigFunction;

// Real environment variables (Docker, CI) win; .env values arrive via $_ENV from phpdotenv.
$env = static function (string $key, string $default = ''): string {
    $value = getenv($key);
    if ($value === false) {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }

    return is_string($value) ? $value : $default;
};
$root = dirname(__DIR__);

return [
    'settings' => [
        'env' => $env('APP_ENV', 'production'),
        'debug' => filter_var($env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOL),
        'root' => $root,
    ],

    PDO::class => static fn(): PDO => ConnectionFactory::create(
        $env('DB_HOST', '127.0.0.1'),
        (int) $env('DB_PORT', '3306'),
        $env('DB_NAME', 'addressbook'),
        $env('DB_USER', 'addressbook'),
        $env('DB_PASS'),
    ),

    Migrator::class => static fn(ContainerInterface $c): Migrator => new Migrator(
        $c->get(PDO::class),
        $root . '/database/migrations',
        $root . '/database/seeds/contacts.sql',
    ),

    Session::class => static fn(): Session => new NativeSession(
        filter_var($env('SESSION_SECURE', 'false'), FILTER_VALIDATE_BOOL),
    ),

    ResponseFactoryInterface::class => static fn(): ResponseFactoryInterface => new ResponseFactory(),

    Twig::class => static function (ContainerInterface $c) use ($root): Twig {
        /** @var array{env: string, debug: bool} $settings */
        $settings = $c->get('settings');
        $twig = Twig::create($root . '/templates', [
            'cache' => $settings['env'] === 'production' ? sys_get_temp_dir() . '/addressbook-twig' : false,
            'strict_variables' => $settings['debug'],
        ]);

        $csrf = $c->get(Csrf::class);
        $twig->getEnvironment()->addFunction(new TwigFunction('csrf_token', static fn(): string => $csrf->token()));
        $twig->getEnvironment()->addFunction(new TwigFunction('asset', static function (string $path) use ($root): string {
            // Cache-busting query string, so browsers pick up new CSS/JS after a change.
            $mtime = @filemtime($root . '/public/' . $path);

            return '/' . $path . ($mtime === false ? '' : '?v=' . $mtime);
        }));

        return $twig;
    },
];
