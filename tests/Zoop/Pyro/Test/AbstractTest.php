<?php

namespace Zoop\Pyro\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Pyro\Test\ServiceManager;
use Zend\Console\Console;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 */
abstract class AbstractTest extends AbstractHttpControllerTestCase
{
    protected static $serviceConfig;
    protected static $serviceManager;
    protected static $documentManager;
    protected static $dbName;

    /**
     * Set up firewall off
     */
    public function setUpFirewallOff()
    {
        $this->setApplicationConfig(
            require __DIR__ . '/../../../test.application.off.config.php'
        );

        ServiceManager::setServiceConfig(require __DIR__ . '/../../../test.application.off.config.php');
        self::$serviceManager = ServiceManager::getServiceManager();

        $this->bootstrap();
    }

    /**
     * Set up firewall on
     */
    public function setUpFirewallOn()
    {
        $this->setApplicationConfig(
            require __DIR__ . '/../../../test.application.on.config.php'
        );

        ServiceManager::setServiceConfig(require __DIR__ . '/../../../test.application.on.config.php');
        self::$serviceManager = ServiceManager::getServiceManager();

        $this->bootstrap();
    }

    /**
     * Set up mode allow on firewall
     *
     * @param boolean $insertIpAddress
     */
    public function setUpModeAllow($insertIpAddress = false)
    {
        $this->setApplicationConfig(
            require __DIR__ . '/../../../test.application.allow.config.php'
        );

        ServiceManager::setServiceConfig(require __DIR__ . '/../../../test.application.allow.config.php');
        self::$serviceManager = ServiceManager::getServiceManager();

        if ($insertIpAddress === true) {
            // insert ip address before Application bootstrap
            $firewall = [
                'ipAddress' => $this->getServiceManager()->get('Config')['zoop']['test']['ipAddress'],
                'isAllowed' => true
            ];
            $this->insertFirewall($firewall);
        }

        $this->bootstrap();
    }

    /**
     * Set up mode deny
     *
     * @param boolean $insertIpAddress
     */
    public function setUpModeDeny($insertIpAddress = false)
    {
        $this->setApplicationConfig(
            require __DIR__ . '/../../../test.application.deny.config.php'
        );

        ServiceManager::setServiceConfig(require __DIR__ . '/../../../test.application.deny.config.php');
        self::$serviceManager = ServiceManager::getServiceManager();

        if ($insertIpAddress === true) {
            // insert ip address before Application bootstrap
            $firewall = [
                'ipAddress' => $this->getServiceManager()->get('Config')['zoop']['test']['ipAddress'],
                'isAllowed' => false
            ];
            $this->insertFirewall($firewall);
        }

        $this->bootstrap();
    }

    public function bootstrap()
    {
        // mark the MVC doesn't use console
        Console::overrideIsConsole(false);

        // bootstrap application
        $application = $this->getServiceManager()
            ->get('Application')
            ->bootstrap();

        // set service manager
        self::$serviceManager  = $application->getServiceManager();
        self::$documentManager = $this->getServiceManager()
            ->get('doctrine.odm.documentmanager.default');
        self::$dbName = $this->getServiceManager()
            ->get('config')['doctrine']['odm']['connection']['default']['dbname'];
    }

    public static function tearDownAfterClass()
    {
        self::clearDb();
    }

    public static function clearDb()
    {
        $documentManager = self::getDocumentManager();

        if ($documentManager instanceof DocumentManager) {
            $collections = $documentManager->getConnection()
                ->selectDatabase(self::getDbName())
                ->listCollections();

            foreach ($collections as $collection) {
                /* @var $collection \MongoCollection */
                $collection->drop();
            }
        }
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        return self::$serviceManager;
    }

    /**
     * @return DocumentManager
     */
    public static function getDocumentManager()
    {
        return self::$documentManager;
    }

    /**
     * @return string
     */
    public static function getDbName()
    {
        return self::$dbName;
    }

    /**
     * Insert firewall
     *
     * @param array $firewall
     */
    public static function insertFirewall($firewall)
    {
        $dm = self::getServiceManager()->get('doctrine.odm.documentmanager.default');
        $qb = $dm->createQueryBuilder('\Zoop\Pyro\Adapters\Doctrine\DataModel\Firewall')
                ->update()
                ->upsert(true)
                ->field('ipAddress')->equals($firewall['ipAddress'])
                ->field('isAllowed')->set($firewall['isAllowed']);
        $query = $qb->getQuery();
        $query->execute();
    }
}
