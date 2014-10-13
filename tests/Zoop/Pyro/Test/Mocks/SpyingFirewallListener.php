<?php

namespace Zoop\Pyro\Test\Mocks;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zoop\Pyro\FirewallListener;
use Zoop\Pyro\FirewallEvent;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 */
class SpyingFirewallListener extends FirewallListener
{
    private $doFirewallWasRequested   = false;
    private $preFirewallWasRequested  = false;
    private $postFirewallWasRequested = false;
    private $isFirewallEnabled = false;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners = [
            $events->attach(FirewallEvent::EVENT_FIREWALL_DISPATCH, [$this, 'doFirewall'], 1),
            $events->attach(FirewallEvent::EVENT_FIREWALL_PRE_PROCESS, [$this, 'preFirewall'], 1),
            $events->attach(FirewallEvent::EVENT_FIREWALL_POST_PROCESS, [$this, 'postFirewall'], 1)
        ];
    }

    /**
     * Executed method on EVENT_FIREWALL_DISPATCH
     */
    public function doFirewall(EventInterface $event)
    {
        $this->doFirewallWasRequested = true;
        
        // check firewall enabled or disabled
        $serviceManager = $this->getServiceLocator();
        $application = $serviceManager->get('Application');
        $request = $application->getRequest();
        
        if ($serviceManager->get('config')['zoop']['pyro']['enable']) {
            $this->isFirewallEnabled = true;
        }
    }

    /**
     * Get $doFirewallWasRequested value
     *
     * @return boolean
     */
    public function getDoFirewallWasRequested()
    {
        return $this->doFirewallWasRequested;
    }

    /**
     * Executed method on EVENT_FIREWALL_PRE_PROCESS
     */
    public function preFirewall(EventInterface $event)
    {
        $this->preFirewallWasRequested = true;
    }

    /**
     * Get $preFirewallWasRequested value
     *
     * @return boolean
     */
    public function getPreFirewallWasRequested()
    {
        return $this->preFirewallWasRequested;
    }

    /**
     * Executed method on EVENT_FIREWALL_POST_PROCESS
     */
    public function postFirewall(EventInterface $event)
    {
        $this->postFirewallWasRequested = true;
    }

    /**
     * Get $postFirewallWasRequested value
     *
     * @return boolean
     */
    public function getPostFirewallWasRequested()
    {
        return $this->postFirewallWasRequested;
    }
    
    /**
     * Get $isFirewallEnabled value
     *
     * @return boolean
     */
    public function isFirewallEnabled()
    {
        return $this->isFirewallEnabled;
    }
}
