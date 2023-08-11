<?php

namespace Local\App\Api;

use Local\Illuminate as I;
use Local\Illuminate\Api\EndpointInterface;

/**
 * Endpoints repository
 */
interface EndpointRepositoryInterface
{
    /**
     * @param string $id
     * @param EndpointInterface $ep
     * @return EndpointRepositoryInterface
     */
    public function with(string $id, I\Api\EndpointInterface $ep): EndpointRepositoryInterface;
    
    /**
     * @param string $id
     * @return I\Api\EndpointInterface
     */
    public function endpoint(string $id): I\Api\EndpointInterface;
}
