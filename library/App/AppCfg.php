<?php

namespace Local\App;

use Local\Illuminate\CfgPlainFile;
use Local\Illuminate\ConfigInterface;

/**
 * App Config
 */
final class AppCfg implements ConfigInterface
{
    /**
     * @var CfgPlainFile
     */
    private CfgPlainFile $origin;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->origin = new CfgPlainFile(__DIR__ . "/../../environment.php");
    }
    
    /**
     * @inheritDoc
     */
    public function fetch(string $path, $default = null)
    {
        return $this->origin->fetch($path, $default);
    }
}
