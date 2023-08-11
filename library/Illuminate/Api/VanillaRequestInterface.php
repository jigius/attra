<?php

namespace Local\Illuminate\Api;

interface VanillaRequestInterface extends RequestInterface
{
    /**
     * @param QueryRequestInterface $q
     * @return VanillaRequestInterface
     */
    public function withQuery(QueryRequestInterface $q): VanillaRequestInterface;
    
    /**
     * @param resource $stream
     * @return VanillaRequestInterface
     */
    public function withBodyAsStream($stream): VanillaRequestInterface;
    
    /**
     * @param string $body
     * @return VanillaRequestInterface
     */
    public function withBody(string $body): VanillaRequestInterface;
}
