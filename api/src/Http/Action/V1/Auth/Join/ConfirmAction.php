<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Validator\Validator;
use App\Http\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Auth\Command\JoinByEmail\Confirm\Command;
use App\Auth\Command\JoinByEmail\Confirm\Handler;

final class ConfirmAction implements RequestHandlerInterface
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
         * @var array{token:?string} $data
         */
        $data = $request->getParsedBody();

        $command = new Command();
        $command->token = $data['token'] ?? '';

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(200);
    }
}
