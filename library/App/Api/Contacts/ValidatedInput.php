<?php

namespace Local\App\Api\Contacts;

use InvalidArgumentException;
use Local\Illuminate as I;
use LogicException;
use Ramsey\Uuid\Uuid;

final class ValidatedInput implements I\Api\ValidatableInputInterface
{
    /**
     * @var array
     */
    private array $i;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->i = [];
    }
    
    /**
     * @inheritDoc
     */
    public function withInput(I\Api\RequestInterface $req): self
    {
       $that = $this->blueprinted();
       $that->i["request"] = $req;
       return $that;
    }
    
    /**
     * @inheritDoc
     * @throws LogicException|InvalidArgumentException
     */
    public function validated(): I\Api\ValidatableInputInterface
    {
        if (!isset($this->i["request"])) {
            throw new LogicException("`request` is not defined");
        }
        $q = $this->i['request']->query();
        if (!$q->has(EndpointInterface::QUERY_PARAM_UUID)) {
            throw
                new InvalidArgumentException(
                    "`" . EndpointInterface::QUERY_PARAM_UUID . "' is not defined",
                    400
                );
        }
        if (!Uuid::isValid($q->param(EndpointInterface::QUERY_PARAM_UUID))) {
            throw
                new InvalidArgumentException(
                    "`" . EndpointInterface::QUERY_PARAM_UUID . "' is invalid",
                    400
                );
        }
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function bagWithErrors(): I\Api\BagWithErrorsInterface
    {
        throw new LogicException("is not implemented");
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
