<?php

return [
    'modules' => [
        'DoctrineModule',
        'DoctrineMongoODMModule',
        'Zoop\Pyro',
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/test.module.config.php',
        ],
    ],
];
