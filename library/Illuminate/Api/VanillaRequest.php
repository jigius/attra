<?php

namespace Local\Illuminate\Api;

use LogicException;

final class VanillaRequest implements VanillaRequestInterface
{
    /**
     * @var array
     */
    private array $i;
    
    /**
     * Cntr
     * @param QueryRequestInterface|null $body
     */
    public function __construct(?QueryRequestInterface $body = null)
    {
        $this->i = [
            "body" => $body
        ];
    }
    
    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function withBodyAsStream($stream): self
    {
        if (!is_resource($stream)) {
            throw new LogicException("invalid type");
        }
        $that = $this->blueprinted();
        $that->i["body@stream"] = $stream;
        return $that;
    }
    
    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function withBody(string $body): self
    {
        $that = $this->blueprinted();
        $that->i["body@string"] = $body;
        return $that;
    }
    
    /**
     * @inheritDoc
     */
    public function withQuery(QueryRequestInterface $q): self
    {
        $that = $this->blueprinted();
        $that->i["query"] = $q;
        return $that;
    }
    
    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function query(): QueryRequestInterface
    {
        if (!isset($this->i["query"])) {
            throw new LogicException("`query` is not defined");
        }
        return $this->i["query"];
    }
    
    /**
     * @inheritDoc
     */
    public function body(): QueryRequestInterface
    {
        if (isset($this->i["body@stream"])) {
            $content = file_get_contents($this->i['body@stream']);
        } elseif (isset($this->i["body@string"])) {
            $content = $this->i["body@string"];
        } else {
            $content = "";
        }
        $data = [];
        $body = $this->i["body"];
        parse_str($content,$data);
        foreach ($data as $name => $value) {
            $body = $body->withParam($name, $value);
        }
        return $body;
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
