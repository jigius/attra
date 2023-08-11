<?php

namespace Local\App\Entity\User\Persistence\Request;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use Jigius\LittleSweetPods\Illuminate\PrnArray;
use Local\App\Entity\User\Persistence;
use PDO as PdoStock;
use PDOStatement;
use DateTimeInterface;
use DateTimeImmutable;
use DateTimeZone;
use DomainException;

/**
 * Appends a data about new user
 */
final class RqNewEntity implements Pdo\RequestInterface
{
	/**
	 * @var Persistence\EntityInterface
	 */
	private Persistence\EntityInterface $entity;

	/**
	 * Cntr
	 * @param Persistence\EntityInterface $entity
	 */
	public function __construct(Persistence\EntityInterface $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * @inheritDoc
	 * @throws DomainException
	 */
	public function executed(PdoStock $pdo): PDOStatement
	{
		$i =
			$this
				->entity
				->printed(
					new PrnArray(
						['id', 'uuid', 'created']
					)
				);
		$d = [];
        if (isset($i['id'])) {
            if (!is_int($i['id'])) {
                throw new DomainException("`id` type is invalid");
            }
			$d[Persistence\EntityInterface::FIELD_ID] = $i['id'];
		}
		if (isset($i['uuid'])) {
			if (!is_string($i['uuid'])) {
				throw new DomainException("`uuid` type is invalid");
			}
            $d[Persistence\EntityInterface::FIELD_UUID] = $i['uuid'];
		}
		if (isset($i['created'])) {
			if (!$i['created'] instanceof DateTimeInterface) {
				throw new DomainException("`created` type is invalid");
			}
			$d[Persistence\EntityInterface::FIELD_CREATED] =
                (function (DateTimeInterface $dt): string {
                    // converts datetime to UTC
                    return
                        (new DateTimeImmutable())
                            ->setTimezone(new DateTimeZone("UTC"))
                            ->setTimestamp($dt->getTimestamp())
                            ->format("Y-m-d H:i:s");
                })($i['created']);
		}
		$stmt =
			$pdo
				->prepare(
					implode(
						" ",
						[
							"INSERT INTO `users`",
								"(",
									implode(
										",",
										array_map(
											function (string $itm): string {
												return "`$itm`";
											},
											array_keys($d)

										)
									),
								")",
							"VALUES",
								"(",
									implode(
										",",
										array_map(
											function (string $itm): string {
												return ":$itm";
											},
											array_keys($d)
										)
									),
								")"
						]
					)
				);
		$values = [];
		array_walk(
			$d,
			function ($val, $key) use (&$values) {
				$values[":$key"] = $val;
			}
		);
		$stmt->execute($values);
		return $stmt;
	}
}
