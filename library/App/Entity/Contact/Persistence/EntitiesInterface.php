<?php

namespace Local\App\Entity\Contact\Persistence;

use Iterator;

/**
 *
 */
interface EntitiesInterface extends Iterator
{
    /**
     * @inheritDoc
     */
    public function current(): EntityInterface;
}
