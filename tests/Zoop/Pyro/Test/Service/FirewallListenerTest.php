<?php

namespace Zoop\Pyro\Test\Service;

use Zoop\Pyro\Test\AbstractTest;
use Zoop\Pyro\Firewall;
use Zoop\Pyro\FirewallEvent;
use Zoop\Pyro\Test\Mocks\SpyingFirewallListener;
use Zend\EventManager\EventManager;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class FirewallListenerTest extends AbstractTest
{
    protected $firewall;

    /**
     * Test trigger EVENT_FIREWALL_DISPATCH
     */
    public function testTriggerFirewallDispatch()
    {
        self::setUpFirewallOff();
        
        $spyListener  = new SpyingFirewallListener;
        $spyListener->setServiceLocator($this->getServiceManager());
        
        $eventManager = new EventManager;
        $eventManager->attach($spyListener);
        $eventManager->trigger(FirewallEvent::EVENT_FIREWALL_DISPATCH);

        $this->assertTrue($spyListener->isFirewallDispatch());
    }

    /**
     * Test trigger EVENT_FIREWALL_PRE_PROCESS
     */
    public function testTriggerFirewallPreProcess()
    {
        $spyListener = new SpyingFirewallListener;

        $eventManager = new EventManager;
        $eventManager->attach($spyListener);
        $eventManager->trigger(FirewallEvent::EVENT_FIREWALL_PRE_PROCESS);

        $this->assertTrue($spyListener->isFirewallPreProcessRequest());
    }

    /**
     * Test trigger EVENT_FIREWALL_POST_DISPATCH
     */
    public function testTriggerFirewallPostProcess()
    {
        $spyListener = new SpyingFirewallListener;

        $eventManager = new EventManager;
        $eventManager->attach($spyListener);
        $eventManager->trigger(FirewallEvent::EVENT_FIREWALL_POST_PROCESS);

        $this->assertTrue($spyListener->isFirewallPostProcessRequest());
    }


    /**
     * Test if firewall is off
     */
    public function testFirewallOff()
    {
        self::setUpFirewallOff();
        $spyListener  = new SpyingFirewallListener;
        $spyListener->setServiceLocator($this->getServiceManager());
        
        $eventManager = new EventManager;
        $eventManager->attach($spyListener);
        $eventManager->trigger(FirewallEvent::EVENT_FIREWALL_DISPATCH);
        
        $this->assertFalse($spyListener->isFirewallEnabled());
    }

    /**
     * Test if firewall is on
     */
    public function testFirewallOn()
    {
        self::setUpFirewallOn();
        $spyListener  = new SpyingFirewallListener;
        $spyListener->setServiceLocator($this->getServiceManager());
        
        $eventManager = new EventManager;
        $eventManager->attach($spyListener);
        $eventManager->trigger(FirewallEvent::EVENT_FIREWALL_DISPATCH);
        
        $this->assertTrue($spyListener->isFirewallEnabled());
    }

    /**
     * @return Firewall
     */
    public function getFirewall()
    {
        return $this->firewall;
    }

    /**
     * @param Firewall $firewall
     */
    public function setFirewall(Firewall $firewall)
    {
        $this->firewall = $firewall;
    }

    protected function mockApplication($firewallEnabled = false)
    {
        $serviceManager = new ServiceManager;

        //mock event manager
        $mockEventManager = $this->getMock('Zend\\EventManager\\EventManagerInterface');
        //ensure the event manager "trigger" is executed
        $mockEventManager->expects($this->once())->method('trigger');
        $serviceManager->setService('EventManager', $mockEventManager);

        //mock requests
        $mockRequest = $this->getMock('Zend\\Http\\Request');
        $serviceManager->setService('Request', $mockRequest);
        $mockResponse = $this->getMock('Zend\\Http\\PhpEnvironment\\Response');
        $serviceManager->setService('Response', $mockResponse);

        //mock application
        $mockApplication = $this->getMock('Zend\\Mvc\\Application', [], [[], $serviceManager]);
        $mockApplication->method('getEventManager')
            ->willReturn($mockEventManager);
        $serviceManager->setService('Application', $mockApplication);
    }
}
