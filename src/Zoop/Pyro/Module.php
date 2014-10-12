<?php

/**
 * @package Zoop
 */

namespace Zoop\Pyro;

use Zend\Mvc\MvcEvent;
use Zoop\Pyro\FirewallEvent;

/**
 * @since   0.1
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class Module
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $eventManager = $application->getEventManager();

        $eventManager->attach($serviceManager->get('zoop.pyro.firewalllistener'));
        $firewall = $serviceManager->get('zoop.pyro.firewall');
        $eventManager->trigger(FirewallEvent::EVENT_FIREWALL_DISPATCH, null, $firewall);
    }
}
