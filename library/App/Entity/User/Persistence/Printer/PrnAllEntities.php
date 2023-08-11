<?php

namespace Local\App\Entity\User\Persistence\Printer;

use Local\App\Entity\User\Persistence;
use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use LogicException;

/**
 * Extracts (prints) all entities from the persistence layer
 */
final class PrnAllEntities implements Persistence\PrinterEntitiesInterface
{
	/**
	 * @var Pdo\RepositoryInterface
	 */
	private Pdo\RepositoryInterface $r;
	/**
	 * @var array
	 */
	private array $i;

    /**
	 * Cntr
	 */
	public function __construct(Pdo\RepositoryInterface $r)
	{
		$this->r = $r;
		$this->i = [];
	}

	/**
	 * @inheritDoc
	 * @throws LogicException
	 */
	public function finished(): Persistence\EntitiesInterface
	{
        if (!isset($this->i['entity'])) {
            throw new LogicException("`entity` is not defined");
        }
        if (!$this->i['entity'] instanceof Persistence\EntityInterface) {
            throw new LogicException("`entity` type invalid");
        }
		return
            new Persistence\Entities(
                $this
                    ->r
                    ->executed(
                        new Persistence\Request\RqAllEntities()
                    ),
                $this->i['entity']->withPersisted(true)
            );
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
		$that = new self($this->r);
		$that->i = $this->i;
		return $that;
	}
}
