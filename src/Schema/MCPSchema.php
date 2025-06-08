<?php

namespace Superconductor\Schema;

use Spatie\LaravelData\Data;
use Superconductor\Contracts\Versions\MCPSchemaContract;

abstract class MCPSchema extends Data implements MCPSchemaContract
{
    abstract public function toJsonRPC() : array;
}
