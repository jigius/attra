<?php

namespace Local\App\Entity\Contact;

use Local\App\Entity\User;

/**
 * Contact's user
 */
interface UserInterface
{
    /**
     * @return User\EntityInterface
     */
	public function user(): User\EntityInterface;
}
