<?php

declare(strict_types=1);

namespace App\Http\Middleware\Auth;

use LogicException;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

final class Authenticate implements MiddlewareInterface
{
    private const ATTRIBUTE = 'identity';

    private ResourceServer $server;
    private ResponseFactoryInterface $response;

    public function __construct(ResourceServer $server, ResponseFactoryInterface $response)
    {
        $this->server = $server;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader('authorization')) {
            return $handler->handle($request);
        }

        try {
            $request = $this->server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($this->response->createResponse());
        }

        $identity = new Identity((string)$request->getAttribute('oauth_user_id'));

        return $handler->handle($request->withAttribute(self::ATTRIBUTE, $identity));
    }

    public static function identity(ServerRequestInterface $request): ?Identity
    {
        /** @var Identity|null $identity */
        $identity = $request->getAttribute(self::ATTRIBUTE);

        if ($identity !== null && !$identity instanceof Identity) {
            throw new LogicException('Invalid identity.');
        }

        return $identity;
    }
}
