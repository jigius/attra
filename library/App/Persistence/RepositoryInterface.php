<?php

namespace Local\App\Persistence;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;

use PDO as PdoStock;

/**
 * Extends contract for direct access to the stock PDO instance
 */
interface RepositoryInterface extends Pdo\RepositoryInterface
{
    /**
     * @return PdoStock
     */
    public function stock(): PdoStock;
}
