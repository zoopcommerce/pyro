<?php

namespace Zoop\Pyro\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class AbstractTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            require __DIR__ . '/../../../test.application.config.php'
        );
    }
}
