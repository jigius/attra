<?php

namespace Local\Illuminate\Api;

interface QueryRequestInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
    
    /**
     * @param string $name
     * @return string
     */
    public function param(string $name): string;
    
    /**
     * @param string $name
     * @param string $value
     * @return QueryRequestInterface
     */
    public function withParam(string $name, string $value): QueryRequestInterface;
}
