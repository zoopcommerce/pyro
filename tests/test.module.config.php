<?php

$mongoDatabaseName = 'zoop_test';

return [
    'zoop' => [
        'test' => [
            'ipAddress' => '192.168.1.1',
        ]
    ],
    'doctrine' => [
        'odm' => [
            'connection' => [
                'default' => [
                    'dbname' => $mongoDatabaseName,
                ],
            ],
            'configuration' => [
                'default' => [
                    'default_db' => $mongoDatabaseName,
                ]
            ],
        ],
        'driver' => [
            'default' => [
                'class' => 'Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain',
                'paths' => [
                    'Zoop\Pyro\Adapters\Doctrine\DataModel' => __DIR__ . '/../src/Zoop/Pyro/Adapters/Doctrine/DataModel'
                ]
            ]
        ],
    ],
];
