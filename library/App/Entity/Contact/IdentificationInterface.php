<?php

namespace Local\App\Entity\Contact;

/**
 * Contact's identification
 */
interface IdentificationInterface
{
	/**
	 * Returns user's id
	 * @return int
	 */
	public function id(): int;
}
