<?php

namespace Local\App\Entity\User;

use Jigius\LittleSweetPods\Foundation\MediaInterface;

/**
 * User entity
 */
interface EntityInterface extends MediaInterface, IdentificationInterface, UuidInterface
{
}
