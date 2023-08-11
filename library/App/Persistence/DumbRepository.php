<?php

namespace Local\App\Persistence;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use LogicException;
use PDO as PdoStock;
use PDOStatement;

/**
 * Dumb repository
 */
final class DumbRepository implements RepositoryInterface
{
    /**
     * Cntr
     */
    public function __construct()
    {
    }
    
    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function executed(Pdo\RequestInterface $r): PDOStatement
    {
        throw new LogicException("just a dumb :(");
    }
    
    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function stock(): PdoStock
    {
        throw new LogicException("just a dumb :(");
    }
}
