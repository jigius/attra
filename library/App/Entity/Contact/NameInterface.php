<?php

namespace Local\App\Entity\Contact;

/**
 * Contact's name
 */
interface NameInterface
{
	/**
	 * Returns contact's name
	 * @return string
	 */
	public function name(): string;
}
