<?php

namespace Zoop\Pyro\Test;

use Zend\ServiceManager\ServiceManager as ZendServiceManager;
use Zend\Console\Console;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zoop\Pyro\Test\Request;
use Zoop\Pyro\Test\Helper\DataCreator;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 */
class ServiceManager
{
    protected static $serviceConfig = null;

    public static function setServiceConfig($config)
    {
        static::$serviceConfig = $config;
    }

    public static function getServiceManager()
    {
        // configure service manager
        $configuration = static::$serviceConfig ? : require __DIR__ . '/../../../test.application.on.config.php';
        $serviceManager = new ZendServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $configuration);
        $serviceManager->get('ModuleManager')->loadModules();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('request', Request::getRequest());
        $serviceManager->setAllowOverride(false);

        return $serviceManager;
    }
}
