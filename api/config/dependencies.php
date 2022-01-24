<?php

declare(strict_types=1);

use function App\env;
use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ConfigAggregator\ConfigAggregator;

$aggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/common/*.php'),
    new PhpFileProvider(__DIR__ . '/' . env('APP_ENV', 'prod') . '/*.php'),
]);

return $aggregator->getMergedConfig();
