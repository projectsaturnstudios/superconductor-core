<?php

namespace Superconductor\Support\Attributes;

use Superconductor\Schema\Definitions\V20241105\Resources\Resource as V20241105Resources;
use Superconductor\Schema\Definitions\V20250326\Resources\Resource as V20250326Resource;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ReadableResource
{
    public function __construct(
        public readonly string $uri,
        public readonly string $name,
        public readonly string $description,
        public readonly string $mimeType,
    ) {}

    public function toResource(string $protocol): V20241105Resources|V20250326Resource
    {
        switch ($protocol) {
            case '2024-11-05':
                return new V20241105Resources(
                    uri: $this->uri,
                    name: $this->name,
                    description: $this->description,
                    mimeType: $this->mimeType
                );

            case '2025-03-26':
                return new V20250326Resource(
                    uri: $this->uri,
                    name: $this->name,
                    description: $this->description,
                    mimeType: $this->mimeType
                );

        }

        throw new \InvalidArgumentException("Unsupported protocol version: $protocol");
    }


}
