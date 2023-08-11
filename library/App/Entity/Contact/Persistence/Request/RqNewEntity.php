<?php

namespace Local\App\Entity\Contact\Persistence\Request;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use Jigius\LittleSweetPods\Illuminate\PrnArray;
use Local\App\Entity\Contact\Persistence;
use Local\App\Entity\User;
use PDO as PdoStock;
use PDOStatement;
use DateTimeInterface;
use DateTimeImmutable;
use DateTimeZone;
use DomainException;

/**
 * Appends a data about a new contact
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
						['id', 'user', 'name', 'phone', 'created']
					)
				);
		$d = [];
        if (isset($i['id'])) {
            if (!is_int($i['id'])) {
                throw new DomainException("`id` type is invalid");
            }
			$d[Persistence\EntityInterface::FIELD_NAME_ID] = $i['id'];
		}
		if (isset($i['user'])) {
			if (!$i['user'] instanceof User\Persistence\EntityInterface) {
				throw new DomainException("`user` type is invalid");
			}
            $d[Persistence\EntityInterface::FIELD_NAME_USER] = $i['user']->id();
		}
        if (isset($i['name'])) {
            if (!is_string($i['name'])) {
                throw new DomainException("`name` type is invalid");
            }
            $d[Persistence\EntityInterface::FIELD_NAME_NAME] = $i['name'];
        }
        if (isset($i['phone'])) {
            if (!is_string($i['phone'])) {
                throw new DomainException("`phone` type is invalid");
            }
            $d[Persistence\EntityInterface::FIELD_NAME_PHONE] = $i['phone'];
        }
		if (isset($i['created'])) {
			if (!$i['created'] instanceof DateTimeInterface) {
				throw new DomainException("`created` type is invalid");
			}
			$d[Persistence\EntityInterface::FIELD_NAME_CREATED] =
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
							"INSERT INTO `contacts`",
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
