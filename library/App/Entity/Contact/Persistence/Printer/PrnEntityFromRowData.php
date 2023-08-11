<?php

namespace Local\App\Entity\Contact\Persistence\Printer;

use Local\App\Entity\Contact\Persistence;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Prints an entity that constructed from a row persisted data passed to the printer
 */
final class PrnEntityFromRowData implements Persistence\PrinterEntityInterface
{
    /**
     * @var array
     */
    private array $i;
    /**
     * @var Persistence\EntityInterface
     */
    private Persistence\EntityInterface $entity;
    
    /**
     * Cntr
     * @param Persistence\EntityInterface $entity
     */
    public function __construct(Persistence\EntityInterface $entity)
    {
        $this->i = [];
        $this->entity = $entity;
    }
    
    /**
     * @inheritDoc
     * @throws DateTimeImmutable|DateTimeZone
     */
    public function finished(): Persistence\EntityInterface
    {
        $entity = $this->entity;
        if (isset($this->i[Persistence\EntityInterface::FIELD_NAME_ID])) {
            $entity = $entity->withId($this->i[Persistence\EntityInterface::FIELD_NAME_ID]);
        }
        if (isset($this->i[Persistence\EntityInterface::FIELD_NAME_USER])) {
            $entity =
                $entity
                    ->withUser(
                        $entity
                            ->user()
                            ->withId(
                                $this->i[Persistence\EntityInterface::FIELD_NAME_ID]
                            )
                            ->withPersisted(true)
                    );
        }
        if (isset($this->i[Persistence\EntityInterface::FIELD_NAME_NAME])) {
            $entity = $entity->withName($this->i[Persistence\EntityInterface::FIELD_NAME_NAME]);
        }
        if (isset($this->i[Persistence\EntityInterface::FIELD_NAME_PHONE])) {
            $entity = $entity->withPhone($this->i[Persistence\EntityInterface::FIELD_NAME_PHONE]);
        }
        if (isset($this->i[Persistence\EntityInterface::FIELD_NAME_CREATED])) {
            $entity =
                $entity
                    ->withCreated(
                        DateTimeImmutable::createFromFormat(
                            "Y-m-d H:i:s",
                            $this->i[Persistence\EntityInterface::FIELD_NAME_CREATED],
                            new DateTimeZone("UTC")
                        )
                    );
            
        }
        return $entity;
    }
    
    /**
     * @inheritDoc
     */
    public function with(string $key, $val): self
    {
        $that = $this->blueprinted();
        $that->i[$key] = $val;
        return $that;
    }
    
    /**
     * Clones the instance
     * @return $this
     */
    public function blueprinted(): self
    {
        $that = new self($this->entity);
        $that->i = $this->i;
        return $that;
    }
}
