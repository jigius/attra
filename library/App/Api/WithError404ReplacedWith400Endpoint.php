<?php

namespace Local\App\Api;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo\RepositoryInterface;
use Local\Illuminate as I;
use InvalidArgumentException;
use Throwable;

/**
 * Replaces a code exception: 404 replaces with 400
 */
final class WithError404ReplacedWith400Endpoint implements I\Api\EndpointInterface
{
    /**
     * @var I\Api\EndpointInterface
     */
    private I\Api\EndpointInterface $origin;
    
    /**
     * Cntr
     */
    public function __construct(I\Api\EndpointInterface $origin)
    {
        $this->origin = $origin;
    }
    
    /**
     * @inheritDoc
     * @throws InvalidArgumentException|Throwable
     */
    public function processed(I\Api\RequestInterface $req, I\Api\ResponseInterface $resp): I\Api\ResponseInterface
    {
        try {
            return $this->origin->processed($req, $resp);
        } catch (Throwable $ex) {
            if ($ex->getCode() == 404) {
                throw new InvalidArgumentException("invalid args", 400, $ex);
            } else {
                throw $ex;
            }
        }
    }
    
    /**
     * @inheritDoc
     */
    public function withRepository(RepositoryInterface $repo): self
    {
        $that = $this->blueprinted();
        $that->origin = $this->origin->withRepository($repo);
        return $that;
    }
    
    /**
     * Clones the instance
     * @return self
     */
    public function blueprinted(): self
    {
        return new self($this->origin);
        
    }
}
