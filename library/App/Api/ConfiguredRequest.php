<?php

namespace Local\App\Api;

use Local\Illuminate as I;

/**
 * Implements Api Request instance
 */
final class ConfiguredRequest implements I\Api\RequestInterface
{
    /**
     * @var I\Api\VanillaRequestInterface
     */
    private I\Api\VanillaRequestInterface $origin;
    
    public function __construct(array $routeVars)
    {
        $query = new I\Api\VanillaQuery();
        $this
            ->origin =
                (new I\Api\VanillaRequest($query))
                    ->withQuery(
                        (function (array $vars) use ($query): I\Api\QueryRequestInterface {
                            foreach ($vars as $name => $val) {
                                $query = $query->withParam($name, $val);
                            }
                            return $query;
                        })($routeVars)
                    )
                    ->withBody(
                        file_get_contents("php://input")
                    );
    }
    
    /**
     * @inheritDoc
     */
    public function query(): I\Api\QueryRequestInterface
    {
        return $this->origin->query();
    }
    
    /**
     * @inheritDoc
     */
    public function body(): I\Api\QueryRequestInterface
    {
        return $this->origin->body();
    }
}
