<?php

namespace Local\Illuminate\Api;


use Local\App\Persistence\RepositoryInterface;

interface EndpointInterface
{
    /**
     * @param RequestInterface $req
     * @param ResponseInterface $resp
     * @return ResponseInterface
     */
    public function processed(RequestInterface $req, ResponseInterface $resp): ResponseInterface;
    
    /**
     * @param RepositoryInterface $repo
     * @return EndpointInterface
     */
    public function withRepository(RepositoryInterface $repo): EndpointInterface;
}
