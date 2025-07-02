<?php

namespace MCP\Capabilities\CapabilityExecution;

use Spatie\LaravelData\Data;

class CapabilityResult extends Data
{
    public function __construct(
        public readonly array $results = [],
    ){}

    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->results);
    }

    public function get(?string $key = null) : mixed
    {
        if($key) return $this->results[$key] ?? null;
        return $this->results;
    }
}
