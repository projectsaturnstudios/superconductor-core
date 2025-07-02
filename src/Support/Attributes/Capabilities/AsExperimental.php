<?php

namespace MCP\Capabilities\Support\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsExperimental
{
    public function __construct(
        public readonly string $name,

    ) {}

}
