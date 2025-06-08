<?php

namespace Superconductor\Schema\Definitions\V20241105\Base;

use Illuminate\Support\Facades\Log;
use Superconductor\Schema\MCPSchema;
use Superconductor\Schema\Definitions\V20241105\Maps\RequestUnion;

class JSONRPCRequest extends MCPSchema
{
    public static string $type = 'request';
    public function __construct(
        public int|string $id,
        public string $method,
        public ?array $params = null,
    ) {}


    public function toValue(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
            'params' => $this->params ?? [],
        ];
    }

    public function toJsonRPC(): array
    {
        return [
            'jsonrpc' => '2.0',
            ...$this->toValue()
        ];
    }

    public function convertToMCP(): Request
    {
        return RequestUnion::create($this);
    }
}
