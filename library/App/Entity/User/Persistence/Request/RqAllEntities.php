<?php

namespace Local\App\Entity\User\Persistence\Request;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use PDO as PdoStock;
use PDOStatement;

/**
 * Does the fetching data about all Users
 */
final class RqAllEntities implements Pdo\RequestInterface
{
	/**
	 * Cntr
	 */
	public function __construct()
	{
	}

	/**
	 * @inheritDoc
	 */
	public function executed(PdoStock $pdo): PDOStatement
	{
		$sql = [
			"SELECT",
				"*",
			"FROM",
				"`users`",
			"WHERE",
				"TRUE"
		];
		$stmt = $pdo->prepare(implode(" ", $sql));
		$stmt->execute([]);
		return $stmt;
	}
}
