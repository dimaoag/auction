<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Validator\Validator;
use App\Http\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;

final class RequestAction implements RequestHandlerInterface
{
    private Handler $handler;
    private Validator $validator;

    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array{email:?string, password:?string} $data
         */
        $data = $request->getParsedBody();

        $command = new Command();
        $command->email = $data['email'] ?? '';
        $command->password = $data['password'] ?? '';

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}
