<?php

namespace Local\Illuminate\Api;

interface WrappingQueryCapableInterface extends QueryRequestInterface
{
    /**
     * @inheritDoc
     * @return WrappingQueryCapableInterface
     */
    public function withParam(string $name, string $value): WrappingQueryCapableInterface;
    
    /**
     * Defines an original query
     * @param QueryRequestInterface $query
     * @return WrappingQueryCapableInterface
     */
    public function withOrigin(QueryRequestInterface $query): WrappingQueryCapableInterface;
}
