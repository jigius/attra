<?php

namespace Local\App\Entity\User\Persistence;

use Local\App\Entity\User as Vanilla;
use DateTimeInterface;

/**
 * Extends vanilla entity with data are value for db persistence layer
 */
interface EntityInterface extends Vanilla\EntityInterface
{
    public const FIELD_ID = "id";
    public const FIELD_UUID = "uuid";
    public const FIELD_CREATED = "created";
    
	/**
	 * @param DateTimeInterface $dt
	 * @return EntityInterface
	 */
	public function withCreated(DateTimeInterface $dt): EntityInterface;

	/**
     * Defines if the entity is persisted or is not
	 * @param bool $flag
	 * @return EntityInterface
	 */
	public function withPersisted(bool $flag): EntityInterface;

    /**
     * Defines user's id
     * @param int $id
     * @return EntityInterface
     */
	public function withId(int $id): EntityInterface;

    /**
     * Defines user's UUID
     * @param string $uuid
     * @return EntityInterface
     */
	public function withUuid(string $uuid): EntityInterface;
}
