<?php

namespace Local\Illuminate;

use RuntimeException;

/**
 * Config stored in a plain php file
 */
final class CfgPlainFile implements ConfigInterface
{
    /**
     * @var string
     */
    private string $pathFile;
    /**
     * @var array
     */
    private array $i;
    
    /**
     * Cntr
     */
    public function __construct(string $pathFile)
    {
        $this->i = [];
        $this->pathFile = $pathFile;
    }
    
    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function fetch(string $path, $default = null)
    {
        if (!isset($this->i["data"])) {
            $data = @include $this->pathFile;
            if ($data === false || !is_array($data)) {
                throw new RuntimeException("could not read config data from file=`{$this->pathFile}`");
            }
            $this->i['data'] = $data;
        }
        $bc = explode(".", $path);
        $ret = $default;
        $t = $this->i["data"];
        for ($i = 0; $i < count($bc); $i++) {
            $t = $t[$bc[$i]];
            if (!is_array($t)) {
                if ($i === count($bc) - 1) {
                    $ret = $t;
                }
                break;
            } elseif ($i === count($bc) - 1) {
                $ret = $t;
                break;
            }
        }
        return $ret;
    }
}
