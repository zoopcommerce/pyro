<?php

namespace Zoop\Pyro;

use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zoop\Pyro\Exception\IpAddressDeniedException;
use Zoop\Pyro\FirewallEvent;
use Zoop\Pyro\Firewall;

class FirewallListener implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach listeners to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(FirewallEvent::EVENT_FIREWALL_DISPATCH, [$this, 'doFirewall'], 1);
    }

    /**
     * Detach listeners from an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Listen to the "user" event
     *
     * @param EventInterface $event
     * @return mixed
     */
    public function doFirewall(EventInterface $event)
    {
        $serviceManager = $this->getServiceLocator();
        $application = $serviceManager->get('Application');
        $request = $application->getRequest();

        if ($request instanceof ConsoleRequest) {
            return;
        }

        if ($serviceManager->get('config')['zoop']['pyro']['enable']) {
            $firewall = $event->getParams();
            if ($firewall instanceof Firewall) {
                $ipAddress = $this->getIpAddress();
                $this->triggerEvent(FirewallEvent::EVENT_FIREWALL_PRE_PROCESS, $firewall);

                try {
                    $firewall->process($ipAddress);
                    $this->triggerEvent(FirewallEvent::EVENT_FIREWALL_POST_PROCESS, $firewall);
                } catch (IpAddressDeniedException $e) {
                    $application->getResponse()->setStatusCode(403);
                }
            }
        }
    }

    /**
     * If no IP address is explicitly set, Pyro tries to determine
     * the users IP address from the request.
     *
     * @return string
     */
    protected function getIpAddress()
    {
        $application = $this->getServiceLocator()->get('Application');
        $request = $application->getRequest();

        //try client ip and ip behind proxy first
        $ipAddress = $request->getServer('HTTP_CLIENT_IP');
        if (empty($ipAddress)) {
            $ipAddress = $request->getServer('HTTP_X_FORWARDED_FOR');
        }

        //get regular ip if those can't be found.
        //this still isn't 100% accurate
        if (empty($ipAddress)) {
            $ipAddress = $request->getServer('REMOTE_ADDR');
        }

        if (empty($ipAddress)) {
            $application->getResponse()->setStatusCode(403);
        }

        return $ipAddress;
    }

    /**
     * @param string $event
     * @param Firewall $firewall
     */
    protected function triggerEvent($event, Firewall $firewall)
    {
        $this->getServiceLocator()
            ->get('Application')
            ->getEventManager()
            ->trigger($event, null, $firewall);
    }
}
