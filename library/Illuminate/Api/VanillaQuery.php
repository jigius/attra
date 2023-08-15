<?php

namespace Local\Illuminate\Api;

use LogicException;

/**
 * Vanilla implementation
 */
final class VanillaQuery implements QueryRequestInterface
{
    /**
     * @var array
     */
    private array $i;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->i = [];
    }
    
    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->i);
    }
    
    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function param(string $name, string $default = ""): string
    {
        return $this->i[$name] ?? $default;
    }
    
    /**
     * @inheritDoc
     */
    public function withParam(string $name, string $value): self
    {
        $that = $this->blueprinted();
        $that->i[$name] = $value;
        return $that;
    }
    
    /**
     * Clones the instance
     * @return self
     */
    public function blueprinted(): self
    {
        $that = new self();
        $that->i = $this->i;
        return $that;
    }
}
