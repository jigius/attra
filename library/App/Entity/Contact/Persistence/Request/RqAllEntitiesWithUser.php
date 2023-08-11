<?php

namespace Local\App\Entity\Contact\Persistence\Request;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use Local\App\Entity\Contact\Persistence\EntityInterface;
use Local\App\Entity\User;
use PDO as PdoStock;
use PDOStatement;

/**
 * Fetches data about all contacts belong a user
 */
final class RqAllEntitiesWithUser implements Pdo\RequestInterface
{
    /**
     * @var User\EntityInterface
     */
    private User\EntityInterface $user;
    
    /**
     * Cntr
     * @param User\EntityInterface $user
     */
	public function __construct(User\EntityInterface $user)
	{
        $this->user = $user;
	}

	/**
	 * @inheritDoc
	 */
	public function executed(PdoStock $pdo): PDOStatement
	{
        $uid = EntityInterface::FIELD_NAME_USER;
        $created = EntityInterface::FIELD_NAME_CREATED;
		$sql = [
			"SELECT",
				"*",
			"FROM",
				"`contacts`",
			"WHERE",
                "`$uid`=:id",
            "ORDER",
                "BY `$created` DESC"
		];
		$stmt = $pdo->prepare(implode(" ", $sql));
        $stmt->execute([':id' => $this->user->id()]);
		return $stmt;
	}
}
