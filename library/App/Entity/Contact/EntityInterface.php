<?php

namespace Local\App\Entity\Contact;

use Jigius\LittleSweetPods\Foundation\MediaInterface;

/**
 * User entity
 */
interface EntityInterface extends MediaInterface, IdentificationInterface, PhoneInterface, NameInterface, UserInterface
{
}
