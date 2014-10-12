<?php

namespace Zoop\Pyro\Test;

use Zend\Stdlib\Parameters;
use Zend\Http\PhpEnvironment\Request as HttpRequest;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 */
class Request
{
    protected static $request;
    protected static $serverParams;

    public static function setServer($params)
    {
        self::$serverParams = $params;
    }

    public static function getRequest()
    {
        if (!isset(self::$serverParams)) {
            self::$serverParams = [
                'HTTP_X_FORWARDED_FOR' => '192.168.1.1',
                'HTTP_CLIENT_IP' => '192.168.1.1',
                'REMOTE_ADDR' => '192.168.1.1',
            ];
        }

        $httpRequest = new HttpRequest();
        $httpRequest->setServer(new Parameters(self::$serverParams));

        return $httpRequest;
    }
}
