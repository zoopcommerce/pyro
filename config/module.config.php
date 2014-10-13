<?php

$mongoDatabaseName = 'pyro';

return [
    'zoop' => [
        'pyro' => [
            'enable' => true,
            'mode' => 'allow', //allow | deny
            'adapter' => 'zoop.pyro.adapter.doctrine',
        ],
    ],
    'doctrine' => [
        'odm' => [
            'connection' => [
                'default' => [
                    'server' => 'localhost',
                    'port' => '27017',
                    'user' => null,
                    'password' => null,
                    'dbname' => $mongoDatabaseName,
                    'options' => []
                ],
            ],
            'configuration' => [
                'default' => [
                    'metadata_cache' => 'doctrine.cache.array',
                    'driver' => 'doctrine.driver.default',
                    'generate_proxies' => true,
                    'proxy_dir' => __DIR__ . '/../data/proxies',
                    'proxy_namespace' => 'Pyro\Proxy',
                    'generate_hydrators' => true,
                    'hydrator_dir' => __DIR__ . '/../data/hydrators',
                    'hydrator_namespace' => 'Pyro\Hydrator',
                    'default_db' => $mongoDatabaseName,
                ],
            ],
            'documentmanager' => [
                'default' => [
                    'connection' => 'doctrine.odm.connection.default',
                    'configuration' => 'doctrine.odm.configuration.default',
                    'eventmanager' => 'doctrine.eventmanager.default'
                ]
            ],
        ],
        'driver' => [
            'default' => [
                'drivers' => [
                    'Zoop\Pyro\Adapters\Doctrine\DataModel' => 'doctrine.driver.pyro'
                ],
            ],
            'pyro' => [
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'cache' => 'doctrine.cache.array',
                'paths' => [
                    __DIR__ . '/../src/Zoop/Pyro/Adapters/Doctrine/DataModel'
                ]
            ]
        ],
    ],
    'controllers' => [
        'invokables' => [],
    ],
    'service_manager' => [
        'invokables' => [
            'zoop.pyro.firewalllistener' => 'Zoop\Pyro\FirewallListener',
        ],
        'factories' => [
            'zoop.pyro.firewall' => 'Zoop\Pyro\Service\FirewallFactory',
            'zoop.pyro.adapter.doctrine' => 'Zoop\Pyro\Adapters\Doctrine\Service\AdapterFactory'
        ],
        'abstract_factories' => [
        ],
    ],
];
