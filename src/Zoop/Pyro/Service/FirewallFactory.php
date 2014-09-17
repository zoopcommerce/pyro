<?php

namespace Zoop\Pyro\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class FirewallFactory implements FactoryInterface
{
    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get(
                $serviceLocator
                    ->get('config')['zoop']['pyro']['adapter']
            );
        
        //create firewall class and pass adapter

        return $return;
    }
}
