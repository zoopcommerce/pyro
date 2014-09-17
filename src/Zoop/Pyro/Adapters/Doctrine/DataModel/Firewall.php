<?php

namespace Zoop\Pyro\Doctrine\DataModel;

use Zoop\Shard\Stamp\DataModel\CreatedOnTrait;
use Zoop\Shard\Stamp\DataModel\UpdatedOnTrait;
use Zoop\Shard\SoftDelete\DataModel\SoftDeleteableTrait;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 */
class Firewall
{
    use CreatedOnTrait;
    use UpdatedOnTrait;
    use SoftDeleteableTrait;
    
    protected $ipAddress;
}
