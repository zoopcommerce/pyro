<?php

/**
 * Pyro (https://github.com/zoopcommerce/pyro)
 *
 * @link      https://github.com/zoopcommerce/pyro
 * @copyright Copyright (c) 2014 Zoop Pty Ltd
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Zoop\Pyro;

class FirewallEvent
{
    const EVENT_FIREWALL_DISPATCH = 'firewall.dispatch';
    const EVENT_FIREWALL_PRE_PROCESS = 'firewall.preprocess';
    const EVENT_FIREWALL_POST_PROCESS = 'firewall.postprocess';
}
