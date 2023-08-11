<?php

namespace Local\Illuminate\Api;

use JsonException;
use DomainException;

final class VanillaResponse implements ResponseInterface
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
        $this->i = [
            "headers" => []
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function withPayload(array $payload): self
    {
        $that = $this->blueprinted();
        $that->i["payload"] = $payload;
        return $that;
    }
    
    /**
     * @inheritDoc
     */
    public function withCode(int $code): self
    {
        $that = $this->blueprinted();
        $that->i["code"] = $code;
        return $that;
    }
    
    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function output()
    {
        if (isset($this->i['code'])) {
            http_response_code($this->i['code']);
        }
        foreach ($this->i["headers"] as $h) {
            header($h);
        }
        if (isset($this->i['payload'])) {
            try {
                header("Content-Type: application/json; charset=utf-8", true);
                echo json_encode($this->i['payload'], 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $ex) {
                throw new DomainException(
                    "Could not encode a response",
                    500,
                    $ex
                );
            }
        }
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
