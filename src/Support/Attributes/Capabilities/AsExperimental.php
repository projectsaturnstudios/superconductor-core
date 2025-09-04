<?php

namespace Superconductor\Mcp\Support\Attributes\Capabilities;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsExperimental
{
    public function __construct(
        public readonly string $name,

    ) {}

}
