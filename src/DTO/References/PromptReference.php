<?php

namespace Superconductor\Mcp\DTO\References;

use Spatie\LaravelData\Data;

class PromptReference extends ReferenceObject
{
    public function __construct(
        public readonly string $name,
    ) {
        parent::__construct(type: 'ref/prompt');
    }
}
