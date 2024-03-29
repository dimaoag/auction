<?php

declare(strict_types=1);

use App\Auth;
use function App\env;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager;
use Doctrine\Common\EventSubscriber;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return [
    EntityManagerInterface::class => static function (ContainerInterface $container): EntityManagerInterface {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     metadata_dirs:array<array-key, string>,
         *     dev_mode:bool,
         *     proxy_dir:string,
         *     cache_dir:?string,
         *     types:array<string,class-string<Doctrine\DBAL\Types\Type>>,
         *     subscribers:string[],
         *     connection:array<string, mixed>
         * } $settings
         */
        $settings = $container->get('config')['doctrine'];

        /** @psalm-suppress  DeprecatedClass */
        $config = Setup::createConfiguration(
            $settings['dev_mode'],
            $settings['proxy_dir'],
            $settings['cache_dir'] ?
                DoctrineProvider::wrap(new FilesystemAdapter('', 0, $settings['cache_dir'])) :
                DoctrineProvider::wrap(new ArrayAdapter())
        );

        $config->setMetadataDriverImpl(new AttributeDriver($settings['metadata_dirs']));

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        foreach ($settings['types'] as $name => $class) {
            if (!Type::hasType($name)) {
                Type::addType($name, $class);
            }
        }

        $eventManager = new EventManager();

        foreach ($settings['subscribers'] as $name) {
            /** @var EventSubscriber $subscriber */
            $subscriber = $container->get($name);
            $eventManager->addEventSubscriber($subscriber);
        }

        return EntityManager::create(
            $settings['connection'],
            $config,
            $eventManager
        );
    },
    Connection::class => static function (ContainerInterface $container): Connection {
        $em = $container->get(EntityManagerInterface::class);
        return $em->getConnection();
    },

    'config' => [
        'doctrine' => [
            'dev_mode' => false,
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => env('DB_HOST'),
                'user' => env('DB_USER'),
                'password' => env('DB_PASSWORD'),
                'dbname' => env('DB_NAME'),
                'charset' => 'utf-8',
            ],
            'subscribers' => [],
            'metadata_dirs' => [
                __DIR__ . '/../../src/Auth/Entity',
                __DIR__ . '/../../src/OAuth/Entity',
            ],
            'types' => [
                Auth\Entity\User\IdType::NAME => Auth\Entity\User\IdType::class,
                Auth\Entity\User\EmailType::NAME => Auth\Entity\User\EmailType::class,
                Auth\Entity\User\RoleType::NAME => Auth\Entity\User\RoleType::class,
                Auth\Entity\User\StatusType::NAME => Auth\Entity\User\StatusType::class,
            ],
        ],
    ],
];
