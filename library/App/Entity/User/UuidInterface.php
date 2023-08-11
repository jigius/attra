<?php

namespace Local\App\Entity\User;

/**
 * User's UUID
 */
interface UuidInterface
{
	/**
	 * Returns user's UUID
	 * @return string
	 */
	public function uuid(): string;
}
