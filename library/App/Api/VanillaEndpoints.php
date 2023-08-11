<?php

namespace Local\App\Api;

use Local\App\Api;
use Local\Illuminate as I;
use DomainException;

/**
 * Vanilla repository for endpoints
 */
final class VanillaEndpoints implements Api\EndpointRepositoryInterface
{
    /**
     * @var array
     */
    private array $i;
    
    /**
     *
     */
    public function __construct()
    {
        $this->i = [];
    }
    
    /**
     * @inheritDoc
     */
    public function with(string $id, I\Api\EndpointInterface $ep): EndpointRepositoryInterface
    {
        $that = $this->blueprinted();
        $that->i[$id] = $ep;
        return $that;
    }
    
    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function endpoint(string $id): I\Api\EndpointInterface
    {
        if (!isset($this->i[$id])) {
            throw new DomainException("not found", 404);
        }
        return $this->i[$id];
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
