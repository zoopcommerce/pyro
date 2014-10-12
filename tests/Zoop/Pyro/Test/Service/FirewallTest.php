<?php

namespace Zoop\Pyro\Test\Service;

use Zoop\Pyro\Test\AbstractTest;
use Zoop\Pyro\Firewall;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 */
class FirewallTest extends AbstractTest
{
    protected $firewall;

    /**
     * Test getting adapter
     */
    public function testGetAdapter()
    {
        self::setUpFirewallOff();

        $firewall = $this->getServiceManager()
            ->get('zoop.pyro.firewall');
        $this->assertInstanceOf('Zoop\Pyro\Firewall', $firewall);
        $this->assertInstanceOf('Zoop\Pyro\Adapters\AdapterInterface', $firewall->getAdapter());
    }

    /**
     * Test allow mode accept all traffic by default
     */
    public function testAllowModeAccept()
    {
        self::setUpModeAllow();

        $application = $this->getServiceManager()
            ->get('Application');
        $statusCode  = $application->getResponse()->getStatusCode();

        // make sure response status should 200
        $this->assertEquals(200, $statusCode);
    }

    /**
     * Test deny mode disallow traffic by default
     */
    public function testDenyModeDisallow()
    {
        self::setUpModeDeny();

        $application = $this->getServiceManager()
            ->get('Application');
        $statusCode  = $application->getResponse()->getStatusCode();

        // make sure response status should 403
        $this->assertEquals(403, $statusCode);
    }

    /**
     * Test allow mode accept traffic from allowed ip address
     */
    public function testAllowModeAcceptAllowedIp()
    {
        self::setUpModeAllow(true);

        $application = $this->getServiceManager()
            ->get('Application');
        $statusCode  = $application->getResponse()->getStatusCode();

        // make sure response status should 200
        $this->assertEquals(200, $statusCode);
    }

    /**
     * Test deny mode disallow traffic from denied ip address
     */
    public function testDenyModeDisallowDeniedIp()
    {
        self::setUpModeDeny(true);

        $application = $this->getServiceManager()
            ->get('Application');
        $statusCode  = $application->getResponse()->getStatusCode();

        // make sure response status should 403
        $this->assertEquals(403, $statusCode);
    }

    /**
     * Test deny an ip address, then check it with isDenied()
     */
    public function testDenyIpAddress()
    {
        self::setUpFirewallOff();

        $ipAddress = $this->getServiceManager()->get('Config')['zoop']['test']['ipAddress'];
        $firewall  = $this->getServiceManager()
            ->get('zoop.pyro.firewall');
        $firewall->deny($ipAddress);

        $this->assertTrue($firewall->isDenied($ipAddress));
    }

    /**
     * Test allow an ip address, then check it with isAllowed()
     */
    public function testAllowIpAddress()
    {
        self::setUpFirewallOff();

        $ipAddress = $this->getServiceManager()->get('Config')['zoop']['test']['ipAddress'];
        $firewall  = $this->getServiceManager()
            ->get('zoop.pyro.firewall');
        $firewall->allow($ipAddress);

        $this->assertTrue($firewall->isAllowed($ipAddress));
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
}
