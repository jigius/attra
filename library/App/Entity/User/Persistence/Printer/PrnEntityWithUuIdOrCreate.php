<?php

namespace Local\App\Entity\User\Persistence\Printer;

use Jigius\LittleSweetPods\Illuminate\ArrayMedia;
use Local\App\Entity\User\Persistence;
use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use DomainException;
use LogicException;
use InvalidArgumentException;

/**
 * Extracts (prints) entity with specified UUID from the persistence layer or creates it if it was not found
 */
final class PrnEntityWithUuIdOrCreate implements Persistence\PrinterEntityInterface
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
     * @var Persistence\PrinterEntityInterface
     */
    private Persistence\PrinterEntityInterface $origin;
    
    /**
	 * Cntr
	 */
	public function __construct(Pdo\RepositoryInterface $r, Persistence\PrinterEntityInterface $origin)
	{
		$this->r = $r;
		$this->i = [];
        $this->origin = $origin;
	}

	/**
	 * @inheritDoc
	 * @throws LogicException|DomainException|InvalidArgumentException
	 */
	public function finished(): Persistence\EntityInterface
	{
        try {
            return (new ArrayMedia($this->i))->printed($this->origin);
        } catch (DomainException $ex) {
            if ($ex->getCode() == 404) {
                if (!isset($this->i['uuid'])) {
                    throw new LogicException("`uuid` is not defined");
                }
                if (!is_string($this->i['uuid'])) {
                    throw new LogicException("`uuid` type invalid");
                }
                $this
                    ->r
                    ->executed(
                        new Persistence\Request\RqNewEntity(
                            (new Persistence\Entity())
                                ->withUuid($this->i["uuid"])
                        )
                    );
                return $this->finished();
            } else {
                throw $ex;
            }
        }
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
		$that = new self($this->r, $this->origin);
		$that->i = $this->i;
		return $that;
	}
}
