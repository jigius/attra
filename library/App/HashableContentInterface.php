<?php

namespace Local\App;

interface HashableContentInterface
{
    /**
     * Calculates a hash
     * @param string $method
     * @return string
     */
    public function hash(string $method = "crc32"): string;
}
