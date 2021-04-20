<?php

declare(strict_types=1);

use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    ResponseFactoryInterface::class => static fn (): ResponseFactoryInterface => new ResponseFactory(),
];
