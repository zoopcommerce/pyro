<?php

namespace Zoop\Pyro\Adapters\Doctrine\DataModel;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 *
 * @since   0.1
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 *
 * @ODM\Document(collection="Firewall")
 */
class Firewall
{
    /**
     * @ODM\Id(strategy="NONE")
     */
    protected $ipAddress;

    /**
     * @ODM\Boolean
     */
    protected $isAllowed;

    /**
     * @return string $ipAddress
     */
    public function getIpAddress()
    {
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
     * Get Allowed
     *
     * @return boolean $allowed
     */
    public function isAllowed()
    {
        return $this->isAllowed;
    }

    /**
     * Set allowed
     *
     * @param boolean $isAllowed
     */
    public function setIsAllowed($isAllowed)
    {
        $this->isAllowed = $isAllowed;
    }
}
