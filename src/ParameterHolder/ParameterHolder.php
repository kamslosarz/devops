<?php

namespace Application\ParameterHolder;

class ParameterHolder implements \ArrayAccess, \Countable, \JsonSerializable, \Iterator
{
    protected $parameters = [];

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function __get($parameter)
    {
        return isset($this->parameters[$parameter]) ? $this->parameters[$parameter] : null;
    }

    public function __set($name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    public function count(): int
    {
        return count($this->parameters);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->parameters[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->parameters[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->parameters[$offset] = $value;

        return $this;
    }

    public function offsetUnset($offset)
    {
        unset($this->parameters[$offset]);

        return $this;
    }

    public function add(array $parameters = []): self
    {
        $this->parameters = array_merge($parameters, $this->parameters);

        return $this;
    }

    public function jsonSerialize(): string
    {
        return json_encode($this->parameters);
    }

    public function rewind()
    {
        reset($this->parameters);
    }

    public function current()
    {
        return current($this->parameters);
    }

    public function key()
    {
        return key($this->parameters);
    }

    public function next()
    {
        return next($this->parameters);
    }

    public function valid()
    {
        $key = key($this->parameters);

        return ($key !== null && $key !== false);
    }

    public function toArray(): array
    {
        return $this->parameters;
    }
}