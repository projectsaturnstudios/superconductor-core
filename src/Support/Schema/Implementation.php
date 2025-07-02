<?php

namespace MCP\Schema;

use Spatie\LaravelData\Data;

class Implementation extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $version,
    ) {}
}
