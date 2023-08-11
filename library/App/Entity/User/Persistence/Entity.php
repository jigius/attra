<?php

namespace Local\App\Entity\User\Persistence;

use DateTimeInterface;
use Jigius\LittleSweetPods\Foundation\PrinterInterface;
use Jigius\LittleSweetPods\Illuminate\ArrayMedia;
use Jigius\LittleSweetPods\Illuminate\PrnWithSuppressedFinished;
use DateTimeImmutable;
use Ramsey\Uuid;
use DateTimeZone;
use Exception;
use DomainException;

/**
 * User-entity with persistence into Db capable
 */
final class Entity implements EntityInterface
{
	/**
	 * @var array
	 */
	private array $i;
    
    /**
     * Cntr
     */
	public function __construct()
	{
		$this->i = [
			'persisted' => false
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
     * @throws DomainException
	 */
	public function uuid(): string
	{
        if (!isset($this->i['uuid'])) {
            throw new DomainException("`uuid` not defined", 404);
        }
        return $this->i['uuid'];
	}

	/**
	 * @inheritDoc
	 */
	public function withUuid(string $uuid): self
	{
        $that = $this->blueprinted();
        $that->i['uuid'] = $uuid;
        return $that;
	}

    /**
	 * @inheritDoc
	 * @throws Exception|DomainException
	 */
	public function printed(PrinterInterface $p)
	{
        if (!isset($this->i['uuid'])) {
            throw new DomainException("`uuid` is mandatory");
        }
        if (!is_string($this->i['uuid']) || !Uuid\Uuid::isValid($this->i['uuid'])) {
            throw new DomainException("`uuid` is invalid");
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
