<?php

namespace Local\App\Entity\User\Persistence\Printer;

use Jigius\LittleSweetPods\Illuminate\ArrayMedia;
use Local\App\Entity\User\Persistence;
use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use PDO as PdoStock;
use DomainException;
use LogicException;
use InvalidArgumentException;

/**
 * Extracts (prints) entity with specified UUID from the persistence layer
 */
final class PrnEntityWithUuId implements Persistence\PrinterEntityInterface
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
	 * @throws LogicException|DomainException|InvalidArgumentException
	 */
	public function finished(): Persistence\EntityInterface
	{
        if (!isset($this->i['entity'])) {
            throw new LogicException("`entity` is not defined");
        }
        if (!$this->i['entity'] instanceof Persistence\Entity) {
            throw new LogicException("`entity` type invalid");
        }
        if (!isset($this->i['uuid'])) {
            throw new LogicException("`uuid` is not defined");
        }
        if (!is_string($this->i['uuid'])) {
            throw new LogicException("`uuid` type invalid");
        }
		$entities = $this->r->executed(new Persistence\Request\RqEntityWithUuid($this->i['uuid']));
		if ($entities->rowCount() === 0) {
			throw new DomainException("data not found", 404);
		}
        if ($entities->rowCount() !== 1) {
            throw new LogicException("data consistency is violation");
        }
        return
            (new ArrayMedia($entities->fetch(PdoStock::FETCH_ASSOC)))
                ->printed(
                    new PrnEntityFromRowData($this->i['entity']->withPersisted(true))
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
