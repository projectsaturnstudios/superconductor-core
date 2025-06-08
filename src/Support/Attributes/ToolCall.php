<?php

namespace Superconductor\Support\Attributes;

use Superconductor\Schema\Definitions\V20241105\Tools\Tool as V20241105Tool;
use Superconductor\Schema\Definitions\V20250326\Tools\Tool as V20250326Tool;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ToolCall
{
    public function __construct(
        public readonly string $tool,
        public readonly string $description,
        public readonly array $inputSchema = [],
        public readonly ?array $annotations = null,

    ) {}

    public function toTool(?string $protocol = null): V20241105Tool|V20250326Tool
    {
        switch ($protocol) {
            case '2024-11-05':
                return new V20241105Tool(
                    name: $this->tool,
                    description: $this->description,
                    inputSchema: $this->inputSchema
                );

            case '2025-03-26':
            case null:
                return new V20250326Tool(
                    name: $this->tool,
                    description: $this->description,
                    inputSchema: $this->inputSchema,
                    annotations: $this->annotations ?? null
                );

        }

        throw new \InvalidArgumentException("Unsupported protocol version: $protocol");
    }
}
