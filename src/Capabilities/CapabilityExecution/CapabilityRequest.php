<?php

namespace MCP\Capabilities\CapabilityExecution;

use Spatie\LaravelData\Data;

class CapabilityRequest extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly array $params = [],
    ){}

    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->params);
    }

    public function get(?string $key = null) : mixed
    {
        if($key) return $this->params[$key] ?? null;
        return $this->params;
    }
}
