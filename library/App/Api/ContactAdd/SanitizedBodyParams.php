<?php

namespace Local\App\Api\ContactAdd;

use Local\Illuminate\Api;

/**
 * Prepares values for future validation and processing
 */
final class SanitizedBodyParams implements Api\WrappingQueryCapableInterface
{
    /**
     * @var Api\QueryRequestInterface
     */
    private Api\QueryRequestInterface $origin;
    /**
     * @var array
     */
    private array $i;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->i = [
            "query" => new Api\VanillaQuery()
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return $this->origin->has($name);
    }
    
    /**
     * @inheritDoc
     */
    public function param(string $name, string $default = ""): string
    {
        $value = $this->origin->param($name, $default);
        if ($name === EndpointInterface::BODY_PARAM_NAME) {
            $value = trim($value);
        } elseif ($name === EndpointInterface::BODY_PARAM_PHONE) {
            $value = preg_replace("~\D~", "", $value);
        }
        return $value;
    }
    
    /**
     * @inheritDoc
     */
    public function withParam(string $name, string $value): self
    {
        $that = $this->blueprinted();
        $that->origin = $this->origin->withParam($name, $value);
        return $that;
    }
    
    /**
     * @inheritDoc
     */
    public function withOrigin(Api\QueryRequestInterface $query): self
    {
        $that = $this->blueprinted();
        $that->origin = $query;
        return $that;
    }
    
    /**
     * Clones the instance
     * @return self
     */
    public function blueprinted(): self
    {
        $that = new self();
        $that->i = $this->i;
        return $that;
    }
}
