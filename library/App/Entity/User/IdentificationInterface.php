<?php

namespace Local\App\Entity\User;

/**
 * User's identification
 */
interface IdentificationInterface
{
	/**
	 * Returns user's id
	 * @return int
	 */
	public function id(): int;
}
