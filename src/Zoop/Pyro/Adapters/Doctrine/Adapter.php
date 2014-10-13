<?php

namespace Zoop\Pyro\Adapters\Doctrine;

use Zoop\Pyro\Adapters\AdapterInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @since   0.1
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 */
class Adapter implements AdapterInterface
{
    protected $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->setDocumentManager($documentManager);
    }

    /**
     * {@inheritdoc}
     */
    public function removeIpAddress($ipAddress)
    {
        $this->getDocumentManager()
            ->createQueryBuilder('Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall')
            ->remove()
            ->field('ipAddress')->equals($ipAddress)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function removeIpAddresses(array $ipAddresses)
    {
        $this->getDocumentManager()
            ->createQueryBuilder('Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall')
            ->remove()
            ->field('ipAddress')
            ->in($ipAddresses)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed($ipAddress)
    {
        $firewallEntry = $this->getFirewallEntry($ipAddress);

        return is_null($firewallEntry) ? false : $firewallEntry->isAllowed();
    }

    /**
     * {@inheritdoc}
     */
    public function isDenied($ipAddress)
    {
        $firewallEntry = $this->getFirewallEntry($ipAddress);

        // when ip address not exist on database it should return false
        return is_null($firewallEntry) ? false : !$firewallEntry->isAllowed();
    }

    /**
     * {@inheritdoc}
     */
    public function allow($ipAddress)
    {
        $this->getDocumentManager()
            ->createQueryBuilder('Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall')
            ->update()
            ->upsert(true)
            ->field('ipAddress')->equals($ipAddress)
            ->field('isAllowed')->set(true)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function deny($ipAddress)
    {
        $this->getDocumentManager()
            ->createQueryBuilder('Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall')
            ->update()
            ->upsert(true)
            ->field('ipAddress')->equals($ipAddress)
            ->field('isAllowed')->set(false)
            ->getQuery()
            ->execute();
    }

    /**
     * Gets the ip address from the document manager
     *
     * @param string $ipAddress
     * @return string|null
     */
    protected function getFirewallEntry($ipAddress)
    {
        return $this->getDocumentManager()
            ->createQueryBuilder()
            ->find('Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall')
            ->field('ipAddress')->equals($ipAddress)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Get DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     * Set DocumentManager
     *
     * @param DocumentManager $documentManager
     */
    public function setDocumentManager($documentManager)
    {
        $this->documentManager = $documentManager;
    }
}
