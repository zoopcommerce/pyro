<?php

namespace Zoop\Pyro\Test\Adapters\Doctrine;

use Zoop\Pyro\Test\AbstractTest;
use Zoop\Pyro\Adapters\Doctrine\Adapter;

/**
 * @since   0.1
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 */
class AdapterTest extends AbstractTest
{
    protected $ipAddress = '192.168.1.4';
    protected $ipAddresses = [
        '192.168.1.1',
        '192.168.1.2',
        '192.168.1.3'
    ];
    protected $adapter;

    public function setUp()
    {
        self::setUpFirewallOff();

        $this->setAdapter(
            new Adapter($this->getDocumentManager())
        );
    }

    /**
     * Test block an ip address
     */
    public function testDenyExplicitlyDenyIpAddress()
    {
        $ipAddress = $this->getIpAddress();
        // block ip address
        $this->getAdapter()->deny($ipAddress);
        $this->assertTrue($this->getAdapter()->isDenied($ipAddress));

        return $ipAddress;
    }

    /**
     * Test block an ip address
     * Adapter should return false when try to deny not existing ip address
     */
    public function testDenyImplicitlyDenyIpAddress()
    {
        $ipAddress = '192.168.1.5';
        $this->assertFalse($this->getAdapter()->isDenied($ipAddress));
    }

    /**
     * Test allow an ip address successfully
     *
     * @depends testDenyExplicitlyDenyIpAddress
     */
    public function testDenyExplicitlyAllowIpAddress($ipAddress)
    {
        // allow ip address
        $this->getAdapter()->allow($ipAddress);
        $this->assertFalse($this->getAdapter()->isDenied($ipAddress));

        return $ipAddress;
    }

    /**
     * Test remove ipAddress
     *
     * @depends testDenyExplicitlyAllowIpAddress
     */
    public function testRemoveIpAddress($ipAddress)
    {
        $this->getAdapter()->removeIpAddress($ipAddress);

        $dm = $this->getDocumentManager();
        $qb = $dm->createQueryBuilder('\Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall');
        $query = $qb->getQuery();
        $firewallEntry = $query->execute();

        $this->assertEquals(0, count($firewallEntry));
    }

    /**
     * Test remove ipAddresses
     */
    public function testRemoveIpAddresses()
    {
        foreach ($this->getIpAddresses() as $ipAddress) {
            $this->getAdapter()->deny($ipAddress);
        }

        // remove multiple ip addresses that inserted by testAddIpAddresses()
        $this->getAdapter()->removeIpAddresses($this->getIpAddresses());

        $dm = $this->getDocumentManager();
        $qb = $dm->createQueryBuilder('\Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall');
        $query = $qb->getQuery();
        $firewall = $query->execute();

        $this->assertEquals(0, count($firewall));
    }

    /**
     * Test allow an ip address successfully
     */
    public function testAllowExplicitlyAllowIpAddress()
    {
        $ipAddress = $this->getIpAddress();

        // allow ip address
        $this->getAdapter()->allow($ipAddress);
        $this->assertTrue($this->getAdapter()->isAllowed($ipAddress));
    }

    /**
     * Test allow an ip address successfully
     */
    public function testAllowImplicitlyDenyIpAddress()
    {
        $ipAddress = '192.168.1.5';
        $this->assertFalse($this->getAdapter()->isAllowed($ipAddress));
    }

    /**
     * Test allow an ip address successfully
     */
    public function testAllowExplicitlyDenyIpAddress()
    {
        $ipAddress = $this->getIpAddress();

        // allow ip address
        $this->getAdapter()->deny($ipAddress);
        $this->assertFalse($this->getAdapter()->isAllowed($ipAddress));
    }

    /**
     * @return Adapter
     */
    protected function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param Adapter $adapter
     */
    protected function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return string
     */
    protected function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @return array
     */
    protected function getIpAddresses()
    {
        return $this->ipAddresses;
    }

    /**
     * @param string $ipAddress
     */
    protected function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @param array $ipAddresses
     */
    protected function setIpAddresses(array $ipAddresses)
    {
        $this->ipAddresses = $ipAddresses;
    }

    /**
     * Insert ip address for sample
     */
    protected function addIpAddress($ipAddress)
    {
        $dm = $this->getDocumentManager();
        $qb = $dm->createQueryBuilder('\Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall')
            ->insert()
            ->field('ipAddress')->set($this->ipAddress);
        $query = $qb->getQuery();
        $query->execute();
    }

    /**
     * Insert ip addresses for sample
     */
    protected function addIpAddresses()
    {
        foreach ($this->ipAddresses as $ipAddress) {
            $this->addIpAddress($ipAddress);
        }
    }
}
