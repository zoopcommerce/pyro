Pyro
====

[![Build Status](https://api.shippable.com/projects/54138c9df82ab7ebd69c94c4/badge?branchName=master)](https://app.shippable.com/projects/54138c9df82ab7ebd69c94c4/builds/latest)

Pyro is a simple ZF2 firewall module.

Usage
-----

### Module init ###

Just add the following to your **application.config.php** modules

```php
return [
    'modules' => [
        'Zoop\Pyro',
        ...
    ],
    ...
];
```

### Config ###



```php
return [
    'zoop' => [
        'pyro' => [
            'enable'  => true, // turns the firewall on or off.
            'adapter' => 'zoop.pyro.adapter.doctrine', //set active adapter, see below.
        ],
    ],
    ...
];
```

### Adapters ###

Pyro consumes firewall "adapters", which are services that store, retrieve and test IP addresses against. You can create your own adapters by implementing `Zoop\Pyro\Adapter\AdapterInterface`.

