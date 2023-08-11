<?php

namespace Local\App\Api\ContactAdd;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo\RepositoryInterface;
use Local\App\Api;
use Local\Illuminate as I;
use InvalidArgumentException;
use Throwable;

/**
 * Preconfigured endpoint contracts
 */
final class ConfiguredEndpoint implements EndpointInterface
{
    /**
     * @var I\Api\EndpointInterface
     */
    private I\Api\EndpointInterface $origin;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this
            ->origin =
            new Api\WithError404ReplacedWith400Endpoint(
                new VanillaEndpoint(new ValidatedInput())
            );
    }
    
    /**
     * @inheritDoc
     * @throws InvalidArgumentException|Throwable
     */
    public function processed(I\Api\RequestInterface $req, I\Api\ResponseInterface $resp): I\Api\ResponseInterface
    {
        return $this->origin->processed($req, $resp);
    }
    
    /**
     * @inheritDoc
     */
    public function withRepository(RepositoryInterface $repo): I\Api\EndpointInterface
    {
        return $this->origin->withRepository($repo);
    }
}
