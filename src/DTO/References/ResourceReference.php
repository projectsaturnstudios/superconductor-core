<?php

namespace Superconductor\Mcp\DTO\References;

use Spatie\LaravelData\Data;

class ResourceReference extends ReferenceObject
{
    public function __construct(
        public readonly string $uri,
    ) {
        parent::__construct(type: 'ref/resource');
    }
}
