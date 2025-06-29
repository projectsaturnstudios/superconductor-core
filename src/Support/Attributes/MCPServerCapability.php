<?php

namespace Superconductor\Support\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class MCPServerCapability extends MCPCapability
{
    public function __construct(
        public readonly string $route,
    ) {}

    public function is_accessible(): bool
    {
        return true; // MCPServerCapability is always accessible
    }

}
