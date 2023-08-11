<?php

namespace Local\App\Api\Contacts;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo\RepositoryInterface;
use Local\Illuminate as I;
use InvalidArgumentException;

/**
 * Preconfigured endpoint contracts
 */
final class ConfiguredEndpoint implements EndpointInterface
{
    /**
     * @var EndpointInterface
     */
    private EndpointInterface $origin;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->origin = new VanillaEndpoint(new ValidatedInput());
    }
    
    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function processed(I\Api\RequestInterface $req, I\Api\ResponseInterface $resp): I\Api\ResponseInterface
    {
        return $this->origin->processed($req, $resp);
    }
    
    /**
     * @inheritDoc
     */
    public function withRepository(RepositoryInterface $repo): EndpointInterface
    {
        return $this->origin->withRepository($repo);
    }
}
