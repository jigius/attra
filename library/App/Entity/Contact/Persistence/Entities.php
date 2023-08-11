<?php

namespace Local\App\Entity\Contact\Persistence;

use IteratorIterator;
use Jigius\LittleSweetPods\Illuminate\ArrayMedia;
use Local\App\Entity\Contact\Persistence\Printer\PrnEntityFromRowData;
use Traversable;

/**
 *
 */
final class Entities extends IteratorIterator implements EntitiesInterface
{
    /**
     * @var EntityInterface
     */
    private EntityInterface $entity;
    
    /**
     * @param Traversable $iterator
     * @param EntityInterface $entity
     */
    public function __construct(Traversable $iterator, EntityInterface $entity)
    {
        parent::__construct($iterator);
        $this->entity = $entity;
    }

    /**
     * @inheritDoc
     */
    public function current(): EntityInterface
    {
        return
            (new ArrayMedia(parent::current()))
                ->printed(
                    new PrnEntityFromRowData($this->entity->withPersisted(true))
                );
    }
}
