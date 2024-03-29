#!/usr/bin/env php
<?php

declare(strict_types=1);

use function App\env;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

require __DIR__ . '/../vendor/autoload.php';

if ($dsn = env('SENTRY_DSN')) {
    Sentry\init(['dsn' => $dsn]);
}

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$cli = new Application('Console');

if (getenv('SENTRY_DSN')) {
    $cli->setCatchExceptions(false);
}

/**
 * @var string[] $commands
 * @psalm-suppress MixedArrayAccess
 */
$commands = $container->get('config')['console']['commands'];

$entityManager = $container->get(EntityManagerInterface::class);

/** @psalm-suppress  DeprecatedClass */
$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');

foreach ($commands as $name) {
    /** @var Command $command */
    $command = $container->get($name);
    $cli->add($command);
}

$cli->run();
