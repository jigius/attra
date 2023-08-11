<?php

namespace Local\Illuminate\Api;

use InvalidArgumentException;

interface BagWithErrorsInterface
{
    /**
     * @return InvalidArgumentException[]
     */
    public function bag(): array;
    
    /**
     * @param string $key
     * @param InvalidArgumentException $ex
     * @return BagWithErrorsInterface
     */
    public function withError(string $key, InvalidArgumentException $ex): BagWithErrorsInterface;
}
