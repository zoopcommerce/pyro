<?php

namespace Zoop\Pyro\Adapters\Doctrine\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zoop\Pyro\Adapters\Doctrine\Adapter;

/**
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class AdapterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Adapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $documentManager = $serviceLocator->get('doctrine.odm.documentmanager.default');

        return new Adapter($documentManager);
    }
}
