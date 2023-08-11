<?php

namespace Local\App\Entity\Contact\Persistence\Request;

use DomainException;
use Exception;
use Local\App\Entity\Contact\Persistence;
use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo\RequestInterface;
use Local\App\Entity\Contact\Persistence\EntityInterface;
use PDO as PdoStock;
use PDOStatement;

/**
 * Deletes a contact into a persistence layer
 */
final class RqDeleteEntity implements RequestInterface
{
    /**
     * @var EntityInterface
     */
    protected Persistence\EntityInterface $entity;
    
    /**
     * Cntr
     * @param EntityInterface $entity
     */
	public function __construct(Persistence\EntityInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * @inheritDoc
	 * @throws Exception|DomainException
	 */
	public function executed(PdoStock $pdo): PDOStatement
	{
        $id = Persistence\EntityInterface::FIELD_NAME_ID;
        $uid = Persistence\EntityInterface::FIELD_NAME_USER;
        $sql = [
            "DELETE FROM",
                "`contacts`",
            "WHERE",
                "`$id`=:id AND `$uid`=:uid"
        ];
        
		$stmt =
			$pdo
				->prepare(
					implode(" ", $sql)
				);
        $stmt->execute([':id' => $this->entity->id(), ':uid' => $this->entity->user()->id()]);
		return $stmt;
	}
}
