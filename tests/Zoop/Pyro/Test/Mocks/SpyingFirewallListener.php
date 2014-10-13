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
    private $isFirewallDispatch = false;
    private $isFirewallEnabled  = false;
    private $isFirewallPreProcessRequest  = false;
    private $isFirewallPostProcessRequest = false;
    
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
        $this->isFirewallDispatch = true;
        
        // check firewall enabled or disabled
        $serviceManager = $this->getServiceLocator();
        $application = $serviceManager->get('Application');
        $request = $application->getRequest();
        
        if ($serviceManager->get('config')['zoop']['pyro']['enable']) {
            $this->isFirewallEnabled = true;
        }
    }

    /**
     * Get $isFirewallDispatch value
     *
     * @return boolean
     */
    public function isFirewallDispatch()
    {
        return $this->isFirewallDispatch;
    }

    /**
     * Executed method on EVENT_FIREWALL_PRE_PROCESS
     */
    public function preFirewall(EventInterface $event)
    {
        $this->isFirewallPreProcessRequest = true;
    }

    /**
     * Get $isFirewallPreProcessRequest value
     *
     * @return boolean
     */
    public function isFirewallPreProcessRequest()
    {
        return $this->isFirewallPreProcessRequest;
    }

    /**
     * Executed method on EVENT_FIREWALL_POST_PROCESS
     */
    public function postFirewall(EventInterface $event)
    {
        $this->isFirewallPostProcessRequest = true;
    }

    /**
     * Get $isFirewallPostProcessRequest value
     *
     * @return boolean
     */
    public function isFirewallPostProcessRequest()
    {
        return $this->isFirewallPostProcessRequest;
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
