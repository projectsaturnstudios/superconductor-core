<?php

namespace Superconductor\Mcp\DTO\References;

use Spatie\LaravelData\Data;

class ReferenceObject extends Data
{
    public function __construct(
        public readonly string $type,
    ) {}
}
