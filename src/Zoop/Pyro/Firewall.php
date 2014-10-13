<?php

namespace Zoop\Pyro;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zoop\Pyro\Adapters\AdapterInterface;
use Zoop\Pyro\Exception\IpAddressDeniedException;

/**
 * @since   0.1
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class Firewall implements AdapterInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const ALLOW = 'allow';
    const DENY = 'deny';

    protected $adapter;
    protected $mode = self::ALLOW;

    /**
     *
     * @param AdapterInterface $adapter
     * @param string $mode
     */
    public function __construct(AdapterInterface $adapter, $mode = self::ALLOW)
    {
        $this->setAdapter($adapter);
        $this->setMode($mode);
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
     * Processes the firewall request
     *
     * @param string $ipAddress
     * @throws IpAddressDeniedException
     */
    public function process($ipAddress)
    {
        if ($this->getMode() === self::ALLOW) {
            // default allow mode
            if ($this->isDenied($ipAddress)) {
                throw new IpAddressDeniedException('The ip ' . $ipAddress . ' is denied.');
            }
        } else {
            // default disallow mode
            if (!$this->isAllowed($ipAddress)) {
                throw new IpAddressDeniedException('The ip ' . $ipAddress . ' is denied.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed($ipAddress)
    {
        return $this->getAdapter()
            ->isAllowed($ipAddress);
    }

    /**
     * {@inheritdoc}
     */
    public function isDenied($ipAddress)
    {
        return $this->getAdapter()
            ->isDenied($ipAddress);
    }

    /**
     * {@inheritdoc}
     */
    public function deny($ipAddress)
    {
        $this->getAdapter()->deny($ipAddress);
    }

    /**
     * {@inheritdoc}
     */
    public function allow($ipAddress)
    {
        $this->getAdapter()->allow($ipAddress);
    }

    /**
     * {@inheritdoc}
     */
    public function removeIpAddress($ipAddress)
    {
        $this->getAdapter()->removeIpAddress($ipAddress);
    }

    /**
     * {@inheritdoc}
     */
    public function removeIpAddresses(array $ipAddresses)
    {
        $this->getAdapter()->removeIpAddresses($ipAddresses);
    }

    /**
     * Returns the firewall mode: allow or deny
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Sets the firewall mode: allow or deny
     *
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }
}
