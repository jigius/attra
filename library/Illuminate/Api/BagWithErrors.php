<?php

namespace Local\Illuminate\Api;

use InvalidArgumentException;

/**
 * Bag with errors
 */
final class BagWithErrors implements BagWithErrorsInterface
{
    /**
     * @var array
     */
    private array $bag;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->bag = [];
    }
    
    
    /**
     * @inheritDoc
     */
    public function bag(): array
    {
        return $this->bag;
    }
    
    /**
     * @inheritDoc
     */
    public function withError(string $key, InvalidArgumentException $ex): self
    {
        $that = $this->blueprinted();
        $that->bag[$key] = $ex;
        return $that;
    }
    
    /**
     * Clones the instance
     * @return self
     */
    public function blueprinted(): self
    {
        $that = new self();
        $that->bag = $this->bag;
        return $that;
    }
}
