<?php

declare(strict_types=1);

use function App\env;
use Psr\Container\ContainerInterface;
use App\Frontend\FrontendUrlGenerator;

return [
    FrontendUrlGenerator::class => static function (ContainerInterface $container): FrontendUrlGenerator {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{url:string} $config
         */
        $config = $container->get('config')['frontend'];

        return new FrontendUrlGenerator($config['url']);
    },

    'config' => [
        'frontend' => [
            'url' => env('FRONTEND_URL'),
        ],
    ],
];
