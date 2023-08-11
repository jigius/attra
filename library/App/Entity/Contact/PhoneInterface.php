<?php

namespace Local\App\Entity\Contact;

/**
 * Contact's phone
 */
interface PhoneInterface
{
	/**
	 * Returns contact's phone
	 * @return string
	 */
	public function phone(): string;
}
