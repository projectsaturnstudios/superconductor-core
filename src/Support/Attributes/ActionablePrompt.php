<?php

namespace Superconductor\Support\Attributes;

use Superconductor\Schema\Definitions\V20241105\Prompts\Prompt as V20241105Prompt;
use Superconductor\Schema\Definitions\V20250326\Prompts\Prompt as V20250326Prompt;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ActionablePrompt
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly array $arguments = [],

    ) {}

    public function toPrompt(?string $protocol = null): V20241105Prompt|V20250326Prompt
    {
        switch ($protocol) {
            case '2024-11-05':
                return new V20241105Prompt(
                    name: $this->name,
                    description: $this->description,
                    arguments: $this->arguments
                );

            case '2025-03-26':
            case null:
                return new V20250326Prompt(
                    name: $this->name,
                    description: $this->description,
                    arguments: $this->arguments
                );

        }

        throw new \InvalidArgumentException("Unsupported protocol version: $protocol");
    }
}
