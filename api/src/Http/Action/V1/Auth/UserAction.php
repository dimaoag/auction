<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use App\Http\Middleware\Auth\Authenticate;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Http\Exception\UnauthorizedHttpException;

final class UserAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        if ($identity === null) {
            throw new UnauthorizedHttpException($request);
        }

        return new JsonResponse([
            'id' => $identity->id,
        ]);
    }
}
