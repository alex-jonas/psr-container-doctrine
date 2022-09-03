<?php

declare(strict_types=1);

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\Cache\PredisCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\WinCacheCache;
use Doctrine\Common\Cache\XcacheCache;
use Doctrine\Common\Cache\ZendDataCache;
use Doctrine\DBAL\Driver as DbalDriver;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver\SQLite3\Driver;
use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\Migrations\Configuration\Migration\ConfigurationLoader;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Roave\PsrContainerDoctrine\EntityManagerFactory;
use Roave\PsrContainerDoctrine\Migrations\CommandFactory;
use Roave\PsrContainerDoctrine\Migrations\ConfigurationLoaderFactory;
use Roave\PsrContainerDoctrine\Migrations\DependencyFactoryFactory;

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'result_cache' => 'array',
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'hydration_cache' => 'array',
                'driver' => 'orm_default', // Actually defaults to the configuration config key, not hard-coded
                'generate_proxies' => true,
                'proxy_dir' => 'data/cache/DoctrineEntityProxy',
                'proxy_namespace' => 'DoctrineEntityProxy',
                'entity_namespaces' => [],
                'datetime_functions' => [],
                'string_functions' => [],
                'numeric_functions' => [],
                'filters' => [],
                'named_queries' => [],
                'named_native_queries' => [],
                'custom_hydration_modes' => [],
                'naming_strategy' => null,
                'quote_strategy' => null,
                'default_repository_class_name' => EntityRepository::class,
                'repository_factory' => null,
                'class_metadata_factory_name' => ClassMetadataFactory::class,
                'entity_listener_resolver' => null,
                'second_level_cache' => [
                    'enabled' => false,
                    'default_lifetime' => 3600,
                    'default_lock_lifetime' => 60,
                    'file_lock_region_directory' => '',
                    'regions' => [],
                ],
                // List of middlewares doctrine will use to decorate the `Driver` component.
                // (see https://github.com/doctrine/dbal/blob/3.3.2/docs/en/reference/architecture.rst#middlewares)
                'middlewares' => ['app.foo.middleware'],
                'schema_assets_filter' => 'my_schema_assets_filter',
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driver_class' => Driver::class,
//                'wrapper_class' => null,
                'pdo' => null,
                'configuration' => 'orm_default', // Actually defaults to the connection config key, not hard-coded
                'event_manager' => 'orm_default', // Actually defaults to the connection config key, not hard-coded
                'params' => [],
                'doctrine_type_mappings' => [],
                'doctrine_commented_types' => [],
            ],
        ],
        'entity_manager' => [
            'orm_default' => [
                'connection' => 'orm_default', // Actually defaults to the entity manager config key, not hard-coded
                'configuration' => 'orm_default', // Actually defaults to the entity manager config key, not hard-coded
            ],
        ],
        'event_manager' => [
            'orm_default' => [
                'subscribers' => [],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => AnnotationDriver::class,
                'paths' => [
                    __DIR__ . '/Entity/', // If this config is is src/App/ConfigProvider.php
                ],
                'extension' => null,
                'drivers' => [],
                'cache' => 'array',
            ],
        ],
        'cache' => [
            'apcu' => [
                'class' => ApcuCache::class,
                'namespace' => 'psr-container-doctrine',
            ],
            'array' => [
                'class' => ArrayCache::class,
                'namespace' => 'psr-container-doctrine',
            ],
            'filesystem' => [
                'class' => FilesystemCache::class,
                'directory' => 'data/cache/DoctrineCache',
                'namespace' => 'psr-container-doctrine',
            ],
            'memcache' => [
                'class' => MemcacheCache::class,
                'instance' => 'my_memcache_alias',
                'namespace' => 'psr-container-doctrine',
            ],
            'memcached' => [
                'class' => MemcachedCache::class,
                'instance' => 'my_memcached_alias',
                'namespace' => 'psr-container-doctrine',
            ],
            'phpfile' => [
                'class' => PhpFileCache::class,
                'directory' => 'data/cache/DoctrineCache',
                'namespace' => 'psr-container-doctrine',
            ],
            'predis' => [
                'class' => PredisCache::class,
                'instance' => 'my_predis_alias',
                'namespace' => 'psr-container-doctrine',
            ],
            'redis' => [
                'class' => RedisCache::class,
                'instance' => 'my_redis_alias',
                'namespace' => 'psr-container-doctrine',
            ],
            'wincache' => [
                'class' => WinCacheCache::class,
                'namespace' => 'psr-container-doctrine',
            ],
            'xcache' => [
                'class' => XcacheCache::class,
                'namespace' => 'psr-container-doctrine',
            ],
            'zenddata' => [
                'class' => ZendDataCache::class,
                'namespace' => 'psr-container-doctrine',
            ],
            // 'my_cache_provider' => [
            //     'class' => CustomCacheProvider::class, //The class is looked up in the container
            // ],
            'chain' => [
                'class' => ChainCache::class,
                'providers' => ['array', 'redis'], // you can use any provider listed above
                'namespace' => 'psr-container-doctrine', // will be applied to all providers in the chain
            ],
        ],
        'types' => [],
        'migrations' => [
            'orm_default' => [
                'table_storage' => [
                    'table_name' => 'migrations_executed',
                    'version_column_name' => 'version',
                    'version_column_length' => 255,
                    'executed_at_column_name' => 'executed_at',
                    'execution_time_column_name' => 'execution_time',
                ],
                'migrations_paths' => ['My\Migrations' => 'scripts/orm/migrations'],
                'all_or_nothing' => true,
                'check_database_platform' => true,
            ],
        ],
    ],
    'dependencies' => [
        'factories' => [
            Command\CurrentCommand::class => CommandFactory::class,
            Command\DiffCommand::class => CommandFactory::class,
            Command\DumpSchemaCommand::class => CommandFactory::class,
            Command\ExecuteCommand::class => CommandFactory::class,
            Command\GenerateCommand::class => CommandFactory::class,
            Command\LatestCommand::class => CommandFactory::class,
            Command\ListCommand::class => CommandFactory::class,
            Command\MigrateCommand::class => CommandFactory::class,
            Command\RollupCommand::class => CommandFactory::class,
            Command\SyncMetadataCommand::class => CommandFactory::class,
            Command\StatusCommand::class => CommandFactory::class,
            Command\UpToDateCommand::class => CommandFactory::class,
            Command\VersionCommand::class => CommandFactory::class,

            EntityManagerInterface::class => EntityManagerFactory::class,
            DependencyFactory::class => DependencyFactoryFactory::class,
            ConfigurationLoader::class => ConfigurationLoaderFactory::class,

            'my_schema_assets_filter' => static function (): callable {
                /**
                 * Filter out assets (table, sequence) by name from Schema
                 * because ones have no mapping and this cause unwanted create|drop statements in migration
                 * generated with migrations:diff command when compare ORM schema and schema introspected from database
                 */
                return static fn (AbstractAsset|string $asset): bool => ! in_array(
                    $asset instanceof AbstractAsset ? $asset->getName() : $asset,
                    [
                        'sequence_to_generate_value',
                        'table_without_doctrine_mapping',
                    ],
                    true,
                );
            },

            'app.foo.middleware' => static function (): Middleware {
                return new class implements Middleware {
                    public function wrap(DbalDriver $driver): DbalDriver
                    {
                        return $driver;
                    }
                };
            },
        ],
    ],
];
