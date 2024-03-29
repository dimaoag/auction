<?php

declare(strict_types=1);

namespace App\ErrorHandler;

use Throwable;
use App\Sentry;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SentryDecorator implements ErrorHandlerInterface
{
    private ErrorHandlerInterface $next;
    private Sentry $sentry;

    public function __construct(ErrorHandlerInterface $next, Sentry $sentry)
    {
        $this->next = $next;
        $this->sentry = $sentry;
    }

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $this->sentry->capture($exception);

        return ($this->next)(
            $request,
            $exception,
            $displayErrorDetails,
            $logErrors,
            $logErrorDetails
        );
    }
}
