<?php

namespace Local\App\Entity\Contact\Persistence;

use DateTimeInterface;
use Jigius\LittleSweetPods\Foundation\PrinterInterface;
use Jigius\LittleSweetPods\Illuminate\ArrayMedia;
use Jigius\LittleSweetPods\Illuminate\PrnWithSuppressedFinished;
use DateTimeImmutable;
use Local\App\Entity\User;
use DateTimeZone;
use Exception;
use DomainException;

/**
 * Contact entity with persistence into Db capable
 */
final class Entity implements EntityInterface
{
	/**
	 * @var array
	 */
	private array $i;
    
    /**
     * Cntr
     * @param EntityInterface|null $user
     */
	public function __construct(?User\Persistence\EntityInterface $user = null)
	{
		$this->i = [
			'persisted' => false,
            'user' => $user ?? new User\Persistence\Entity()
		];
	}

	/**
	 * @inheritDoc
     * @throws DomainException
	 */
    public function id(): int
    {
        if (!isset($this->i['id'])) {
            throw new DomainException("`id` not defined", 404);
        }
        return $this->i['id'];
    }

	/**
	 * @inheritDoc
	 */
	public function withId(int $id): self
	{
        $that = $this->blueprinted();
        $that->i['id'] = $id;
        return $that;
	}

    /**
     * @inheritDoc
     */
    public function withUid(int $id): self
    {
        $that = $this->blueprinted();
        $that->i['uid'] = $id;
        return $that;
    }
    
    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function name(): string
    {
        if (!isset($this->i['name'])) {
            throw new DomainException("`name` not defined", 404);
        }
        return $this->i['name'];
    }
    
    /**
     * @inheritDoc
     */
    public function withName(string $name): self
    {
        $that = $this->blueprinted();
        $that->i['name'] = $name;
        return $that;
    }
    
    /**
     * @inheritDoc
     */
    public function withPhone(string $phone): self
    {
        $that = $this->blueprinted();
        $that->i['phone'] = $phone;
        return $that;
    }
    
    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function phone(): string
    {
        if (!isset($this->i['phone'])) {
            throw new DomainException("`phone` not defined", 404);
        }
        return $this->i['phone'];
    }
    
    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function user(): User\Persistence\EntityInterface
    {
        if (!isset($this->i['user'])) {
            throw new DomainException("`user` not defined", 404);
        }
        return $this->i['user'];
    }
    
    /**
     * @inheritDoc
     * @return EntityInterface
     */
    public function withUser(User\Persistence\EntityInterface $user): self
    {
        $that = $this->blueprinted();
        $that->i['user'] = $user;
        return $that;
    }
    
    /**
	 * @inheritDoc
	 * @throws Exception|DomainException
	 */
	public function printed(PrinterInterface $p)
	{
        if (!isset($this->i['user'])) {
            throw new DomainException("`user` is mandatory");
        }
        if (!isset($this->i['name'])) {
            throw new DomainException("`name` is mandatory");
        }
        if (mb_strlen($this->i['name']) < EntityInterface::CONSTRAINT_NAME_MINLENGTH) {
            throw new DomainException("`name` value is too short");
        }
        if (mb_strlen($this->i['name']) > EntityInterface::CONSTRAINT_NAME_MAXLENGTH) {
            throw new DomainException("`name` value is too long");
        }
        if (!isset($this->i['phone'])) {
            throw new DomainException("`phone` is mandatory");
        }
        if (mb_strlen($this->i['phone']) !== EntityInterface::CONSTRAINT_PHONE_LENGTH) {
            throw new DomainException("`phone` value is invalid");
        }
		$p =
            (new ArrayMedia($this->i))
                ->printed(
                    new PrnWithSuppressedFinished($p)
                );
        if (!isset($this->i['created'])) {
            $p =
                $p
                    ->with(
                        'created',
                        new DateTimeImmutable("now", new DateTimeZone("UTC"))
                    );
        }
		return
			$p
				->original()
				->finished();
	}

	/**
	 * @inheritDoc
	 */
	public function withCreated(DateTimeInterface $dt): self
	{
		$that = $this->blueprinted();
		$that->i['created'] = $dt;
		return $that;
	}

	/**
	 * @inheritDoc
	 */
	public function withPersisted(bool $flag): self
	{
		$that = $this->blueprinted();
		$that->i['persisted'] = $flag;
		return $that;
	}

	/**
	 * Clones the instance
	 * @return $this
	 */
	public function blueprinted(): self
	{
		$that = new self();
		$that->i = $this->i;
		return $that;
	}
}
