<?php

namespace Zoop\Pyro\Adapter;

interface AdapterInterface
{
    /**
     * @param string $ipAddress
     * @return boolean
     */
    public function isAllowed($ipAddress);
}
