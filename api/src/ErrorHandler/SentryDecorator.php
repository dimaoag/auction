<?php

declare(strict_types=1);

namespace App\ErrorHandler;

use Throwable;
use function Sentry\captureException;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SentryDecorator implements ErrorHandlerInterface
{
    private ErrorHandlerInterface $next;

    public function __construct(ErrorHandlerInterface $next)
    {
        $this->next = $next;
    }

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        captureException($exception);

        return ($this->next)(
            $request,
            $exception,
            $displayErrorDetails,
            $logErrors,
            $logErrorDetails
        );
    }
}
