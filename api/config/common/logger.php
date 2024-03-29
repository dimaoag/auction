<?php

declare(strict_types=1);

use Monolog\Logger;
use function App\env;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Monolog\Processor\ProcessorInterface;
use App\FeatureToggle\FeaturesMonologProcessor;

return [
    LoggerInterface::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     debug:bool,
         *     stderr:bool,
         *     file:string,
         *     processors:string[]
         * } $config
         */
        $config = $container->get('config')['logger'];

        $level = $config['debug'] ? Logger::DEBUG : Logger::INFO;

        $log = new Logger('API');

        if ($config['stderr']) {
            $log->pushHandler(new StreamHandler('php://stderr', $level));
        }

        if (!empty($config['file'])) {
            $log->pushHandler(new StreamHandler($config['file'], $level));
        }

        foreach ($config['processors'] as $class) {
            /** @var ProcessorInterface $processor */
            $processor = $container->get($class);
            $log->pushProcessor($processor);
        }

        return $log;
    },

    'config' => [
        'logger' => [
            'debug' => (bool)env('APP_DEBUG', '0'),
            'file' => null,
            'stderr' => true,
            'processors' => [
                FeaturesMonologProcessor::class,
            ],
        ],
    ],
];
