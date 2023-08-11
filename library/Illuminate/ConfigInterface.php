<?php

namespace Local\Illuminate;

/**
 * Config contract
 */
interface ConfigInterface
{
    /**
     * @param string $path
     * @param $default
     * @return mixed
     */
    public function fetch(string $path, $default = null);
}
