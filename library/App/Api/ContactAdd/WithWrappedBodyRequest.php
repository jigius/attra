<?php

namespace Local\App\Api\ContactAdd;

use Local\Illuminate\Api\QueryRequestInterface;
use Local\Illuminate\Api\RequestInterface;
use Local\Illuminate\Api\WrappingQueryCapableInterface;

/**
 * Adds an extra processing to the original body's query
 */
final class WithWrappedBodyRequest implements RequestInterface
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $origin;
    /**
     * @var WrappingQueryCapableInterface
     */
    private WrappingQueryCapableInterface $query;
    
    /**
     * @param RequestInterface $origin
     * @param WrappingQueryCapableInterface $query
     */
    public function __construct(RequestInterface $origin, WrappingQueryCapableInterface $query)
    {
        $this->origin = $origin;
        $this->query = $query;
    }
    
    /**
     * @inheritDoc
     */
    public function body(): QueryRequestInterface
    {
        return $this->query->withOrigin($this->origin->body());
    }
    
    /**
     * @inheritDoc
     */
    public function query(): QueryRequestInterface
    {
        return $this->origin->query();
    }
}
