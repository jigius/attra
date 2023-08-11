<?php

namespace Local\App\Entity\User\Persistence\Printer;

use Local\App\Entity\User\Persistence;
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
        if (isset($this->i[Persistence\EntityInterface::FIELD_ID])) {
            $entity = $entity->withId($this->i[Persistence\EntityInterface::FIELD_ID]);
        }
        if (isset($this->i[Persistence\EntityInterface::FIELD_UUID])) {
            $entity = $entity->withUuid($this->i[Persistence\EntityInterface::FIELD_UUID]);
        }
        if (isset($this->i[Persistence\EntityInterface::FIELD_CREATED])) {
            $entity =
                $entity
                    ->withCreated(
                        DateTimeImmutable::createFromFormat(
                            "Y-m-d H:i:s",
                            $this->i[Persistence\EntityInterface::FIELD_CREATED],
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
