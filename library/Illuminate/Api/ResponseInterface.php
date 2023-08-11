<?php

namespace Local\Illuminate\Api;

interface ResponseInterface
{
    /**
     * @param array $payload
     * @return ResponseInterface
     */
    public function withPayload(array $payload): ResponseInterface;
    
    /**
     * @param int $code
     * @return ResponseInterface
     */
    public function withCode(int $code): ResponseInterface;
    
    /**
     * @return void
     */
    public function output();
}
