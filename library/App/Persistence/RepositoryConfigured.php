<?php

namespace Local\App\Persistence;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo;
use Local\Illuminate\ConfigInterface;
use PDO as PdoStock;
use PDOStatement;

/**
 * Configured instance of db repository
 */
final class RepositoryConfigured implements RepositoryInterface
{
    /**
     * @var Pdo\RepositoryInterface
     */
    private Pdo\RepositoryInterface $origin;
    /**
     * @var PdoStock
     */
    private PdoStock $stock;
    
    /**
     * Cntr
     * @param ConfigInterface $cfg
     */
    public function __construct(ConfigInterface $cfg)
    {
        $this
            ->stock =
                new PdoStock(
                    $cfg->fetch("pdo.dsn"),
                    $cfg->fetch("pdo.login"),
                    $cfg->fetch("pdo.password"),
                    [
                        PdoStock::ATTR_ERRMODE => PdoStock::ERRMODE_EXCEPTION
                    ]
                
                );
        $this->origin = new Pdo\RptVanilla($this->stock);
    }
    
    /**
     * @inheritDoc
     */
    public function executed(Pdo\RequestInterface $r): PDOStatement
    {
        return $this->origin->executed($r);
    }
    
    /**
     * @inheritDoc
     */
    public function stock(): PdoStock
    {
        return $this->stock;
    }
}
