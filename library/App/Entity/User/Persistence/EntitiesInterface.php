<?php

namespace Local\App\Entity\User\Persistence;

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
