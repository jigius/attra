<?php

namespace Local\Illuminate\Api;

interface RequestInterface
{
    /**
     * @return QueryRequestInterface
     */
    public function query(): QueryRequestInterface;
    
    /**
     * @return QueryRequestInterface
     */
    public function body(): QueryRequestInterface;
}
