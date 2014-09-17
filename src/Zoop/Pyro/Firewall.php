<?php

namespace Zoop\Pyro;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zoop\Pyro\Adapter\AdapterInterface;

class Firewall implements ServiceLocatorAwareInterface
{
    protected $ipAddress;
    protected $adapter;
    protected $isBlocked = false;
    
    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->setAdapter($adapter);
    }

    /**
     * 
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * If no IP address is explicitly set, Pyro tries to determine
     * the users IP address from the request.
     * 
     * @return string
     */
    public function getIpAddress()
    {
        if (!isset($this->ipAddress)) {
            //try client ip and ip behind proxy first
            $this->ipAddress = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP');
            if (empty($this->ipAddress)) {
                $this->ipAddress = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR');
            }

            //get regular ip if those can't be found.
            //this still isn't 100% accurate
            if (empty($this->ipAddress)) {
                $this->ipAddress = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
            }
        }
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }
    
    /**
     * @return boolean
     */
    public function isAllowed()
    {
        return !$this->isBlocked();
    }
    
    /**
     * @return boolean
     */
    public function isBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * @param boolean $isBlocked
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = (boolean) $isBlocked;
    }
}
