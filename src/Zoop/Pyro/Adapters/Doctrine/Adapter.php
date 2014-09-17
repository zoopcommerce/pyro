<?php

namespace Zoop\Pyro\Adapters\Doctrine;

use Zoop\Pyro\Adapter\AdapterInterface;

class Adapter implements AdapterInterface
{
    protected $documentManager;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $documentManager)
    {
        $this->setDocumentManager($documentManager);
    }

    public function isAllowed($ipAddress)
    {
        //get dm and do query with the ip address
    }

    function getDocumentManager()
    {
        return $this->documentManager;
    }

    function setDocumentManager($documentManager)
    {
        $this->documentManager = $documentManager;
    }
}
