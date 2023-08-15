<?php

namespace Local\App;

use RuntimeException;

/**
 * Calculates a hash value for a specified file
 */
final class FileContent implements HashableContentInterface
{
    /**
     * @var string
     */
    private string $pathFile;
    
    /**
     * Cntr
     * @param string $pathFile
     */
    public function __construct(string $pathFile)
    {
        $this->pathFile = $pathFile;
    }
    
    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function hash(string $method = "crc32"): string
    {
        if (($content = file_get_contents($this->pathFile)) === false) {
            throw new RuntimeException("could not read file=`{$this->pathFile}`");
        }
        return hash($method, $content);
    }
}
