<?php

namespace Zoop\Pyro\Adapters;

/**
 * @since   0.1
 * @version $Revision$
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
interface AdapterInterface
{
    /**
     * This function is used in "Deny" mode ie. If all ips are denied
     * by default except the ones explicitly allowed.
     *
     * @param string $ipAddress
     * @return boolean
     */
    public function isAllowed($ipAddress);

    /**
     * This function is used in "Allow" mode ie. If all ips are allowed
     * by default except the ones explicitly denied.
     *
     * @param string $ipAddress
     * @return boolean
     */
    public function isDenied($ipAddress);

    /**
     * Removes an IP Address from firewall storage
     *
     * @param string $ipAddress
     */
    public function removeIpAddress($ipAddress);

    /**
     * Removes multiple IP Address from firewall storage
     *
     * @param array $ipAddresses
     */
    public function removeIpAddresses(array $ipAddresses);

    /**
     * Denies an ip address
     *
     * @param string $ipAddress
     * @void
     */
    public function deny($ipAddress);

    /**
     * Allows an ip address
     *
     * @param string $ipAddress
     * @void
     */
    public function allow($ipAddress);
}
