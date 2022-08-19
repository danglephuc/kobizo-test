<?php

namespace App\Factory;

use Doctrine\ODM\MongoDB\DocumentManager;

class AbstractFactory
{
    protected DocumentManager $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }
}
