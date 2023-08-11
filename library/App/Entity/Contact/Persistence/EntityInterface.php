<?php

namespace Local\App\Entity\Contact\Persistence;

use Local\App\Entity\Contact;
use Local\App\Entity\User;
use DateTimeInterface;

/**
 * Extends vanilla entity with data are value for db persistence layer
 */
interface EntityInterface extends Contact\EntityInterface
{
    public const FIELD_NAME_ID = "id";
    public const FIELD_NAME_USER = "uid";
    public const FIELD_NAME_NAME = "name";
    public const FIELD_NAME_PHONE = "phone";
    public const FIELD_NAME_CREATED = "created";
    
    public const CONSTRAINT_NAME_MINLENGTH = 2;
    public const CONSTRAINT_NAME_MAXLENGTH = 255;
    public const CONSTRAINT_PHONE_LENGTH = 11;
    
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
     * Defines user's ID
     * @param int $id
     * @return EntityInterface
     */
	public function withUid(int $id): EntityInterface;
    
    /**
     * Defines contact's name
     * @param string $name
     * @return EntityInterface
     */
    public function withName(string $name): EntityInterface;
    
    /**
     * Defines contact's name
     * @param string $phone
     * @return EntityInterface
     */
    public function withPhone(string $phone): EntityInterface;
    
    /**
     * Defines contact's name
     * @param User\Persistence\EntityInterface $user
     * @return EntityInterface
     */
    public function withUser(User\Persistence\EntityInterface $user): EntityInterface;
    
    /**
     * @inheritDoc
     * @return User\Persistence\EntityInterface
     */
    public function user(): User\Persistence\EntityInterface;
}
