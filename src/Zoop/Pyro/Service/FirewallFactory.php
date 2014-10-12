<?php

namespace Zoop\Pyro\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zoop\Pyro\Firewall;

/**
 * @since   0.1
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class FirewallFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config')['zoop']['pyro'];
        $adapter = $serviceLocator->get($config['adapter']);

        //create firewall class and pass adapter
        return new Firewall($adapter, $config['mode']);
    }
}
