<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Csrf;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/** Rejects state-changing requests that don't carry this session's CSRF token. */
final class CsrfMiddleware implements MiddlewareInterface
{
    private const array SAFE_METHODS = ['GET', 'HEAD', 'OPTIONS'];

    public function __construct(
        private readonly Csrf $csrf,
        private readonly ResponseFactoryInterface $responses,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), self::SAFE_METHODS, true)) {
            return $handler->handle($request);
        }

        $token = $request->getHeaderLine(Csrf::HEADER);
        if ($token === '') {
            $body = $request->getParsedBody();
            $token = is_array($body) && is_string($body[Csrf::FIELD] ?? null) ? $body[Csrf::FIELD] : '';
        }

        if ($this->csrf->isValid($token)) {
            return $handler->handle($request);
        }

        $response = $this->responses->createResponse(403);
        if (str_contains($request->getHeaderLine('Accept'), 'application/json')) {
            $response->getBody()->write((string) json_encode(['error' => 'Invalid or missing CSRF token.']));

            return $response->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write('Your session has expired. Please go back, reload the page and try again.');

        return $response->withHeader('Content-Type', 'text/plain; charset=utf-8');
    }
}
