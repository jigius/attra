<?php

namespace Local\App\Entity\User\Persistence;

use Jigius\LittleSweetPods\Foundation as F;

/**
 * Corrects result's type
 */
interface PrinterEntityInterface extends F\PrinterInterface
{
	/**
	 * @inheritDoc
	 * @return EntityInterface
	 */
	public function finished(): EntityInterface;
}
