<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Csrf;
use App\Http\Session\ArraySession;
use App\Http\Session\Session;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;

abstract class TestCase extends BaseTestCase
{
    /** @var App<ContainerInterface> */
    protected App $app;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var callable(array<string, mixed>): App<ContainerInterface> $createApp */
        $createApp = require dirname(__DIR__) . '/bootstrap/app.php';
        $this->app = $createApp([Session::class => new ArraySession()]);
    }

    protected function container(): ContainerInterface
    {
        return $this->app->getContainer() ?? throw new \RuntimeException('No container.');
    }

    protected function csrfToken(): string
    {
        return $this->container()->get(Csrf::class)->token();
    }

    /**
     * Sends a JSON API request, the way public/assets/app.js does.
     *
     * @param array<string, mixed>|null $body
     * @param array<string, string>     $headers
     */
    protected function json(string $method, string $uri, ?array $body = null, array $headers = []): ResponseInterface
    {
        $request = (new ServerRequestFactory())->createServerRequest($method, $uri)
            ->withHeader('Accept', 'application/json')
            ->withHeader('X-CSRF-Token', $headers['X-CSRF-Token'] ?? $this->csrfToken());

        if ($body !== null) {
            $request = $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody((new StreamFactory())->createStream(json_encode($body, JSON_THROW_ON_ERROR)));
        }

        return $this->app->handle($request);
    }

    /**
     * Sends a plain HTML request, the way a browser without JavaScript does.
     *
     * @param array<string, mixed>|null $form
     */
    protected function html(string $method, string $uri, ?array $form = null): ResponseInterface
    {
        $request = (new ServerRequestFactory())->createServerRequest($method, $uri)
            ->withHeader('Accept', 'text/html');
        if ($form !== null) {
            $request = $request
                ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
                ->withParsedBody($form);
        }

        return $this->app->handle($request);
    }

    /** @return array<mixed> */
    protected function decode(ResponseInterface $response): array
    {
        $data = json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);

        return is_array($data) ? $data : [];
    }
}
