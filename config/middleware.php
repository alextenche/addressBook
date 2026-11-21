<?php

declare(strict_types=1);

use App\Http\Middleware\CsrfMiddleware;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

// Slim runs middleware last-added-first, so read this list from the bottom up.
return static function (App $app, bool $debug, bool $logErrors = true): void {
    $app->addRoutingMiddleware();
    $app->add(CsrfMiddleware::class);            // needs the parsed body, and runs after method override
    $app->add(new MethodOverrideMiddleware());   // HTML forms send _METHOD=PUT / DELETE
    $app->addBodyParsingMiddleware();            // JSON and form bodies → getParsedBody()
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
    $app->add(SecurityHeadersMiddleware::class);
    // Outermost: error details only when APP_DEBUG=true; errors are logged (except in tests).
    $app->addErrorMiddleware($debug, $logErrors, $logErrors);
};
