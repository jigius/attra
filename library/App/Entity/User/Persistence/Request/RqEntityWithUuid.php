<?php

namespace Local\App\Entity\User\Persistence\Request;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use Local\App\Entity\User\Persistence\EntityInterface;
use PDO as PdoStock;
use PDOStatement;

/**
 * Does the fetching data about one User by its UUID
 */
final class RqEntityWithUuid implements Pdo\RequestInterface
{
	/**
	 * @var string
	 */
	private string $uuid;

	/**
	 * Cntr
	 * @param string $uuid
	 */
	public function __construct(string $uuid)
	{
		$this->uuid = $uuid;
	}

	/**
	 * @inheritDoc
	 */
	public function executed(PdoStock $pdo): PDOStatement
	{
        $uuid = EntityInterface::FIELD_UUID;
		$sql = [
			"SELECT",
				"*",
			"FROM",
				"`users`",
			"WHERE",
				"`$uuid`=:id"
		];
		$stmt = $pdo->prepare(implode(" ", $sql));
		$stmt->execute([':id' => $this->uuid]);
		return $stmt;
	}
}
