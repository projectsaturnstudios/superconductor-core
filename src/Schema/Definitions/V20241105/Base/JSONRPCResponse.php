<?php

namespace Superconductor\Schema\Definitions\V20241105\Base;

use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\MCPSchema;

class JSONRPCResponse extends MCPSchema
{
    public static string $type = 'result';
    public function __construct(
        public int|string $id,
        public ?array $result = null,
    ) {}


    public function toValue(): array
    {
        return $this->result;
    }

    public function toJsonRPC(): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->id,
            'result' => $this->toValue(),
        ];
    }
}
