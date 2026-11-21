<?php

declare(strict_types=1);

use App\Http\Controller\ContactController;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

return static function (App $app): void {
    $app->get('/', [ContactController::class, 'index']);
    $app->get('/contacts', [ContactController::class, 'list']);
    $app->get('/contacts/new', [ContactController::class, 'new']);
    $app->post('/contacts', [ContactController::class, 'store']);
    $app->get('/contacts/{id:[0-9]+}', [ContactController::class, 'show']);
    $app->get('/contacts/{id:[0-9]+}/edit', [ContactController::class, 'edit']);
    $app->map(['PUT', 'PATCH'], '/contacts/{id:[0-9]+}', [ContactController::class, 'update']);
    $app->delete('/contacts/{id:[0-9]+}', [ContactController::class, 'destroy']);

    $app->get('/health', function ($request, Response $response): Response {
        $response->getBody()->write('ok');

        return $response->withHeader('Content-Type', 'text/plain');
    });
};
