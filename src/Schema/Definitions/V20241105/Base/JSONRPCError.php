<?php

namespace Superconductor\Schema\Definitions\V20241105\Base;

use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\MCPSchema;

class JSONRPCError extends MCPSchema
{
    public static string $type = 'result';

    public function __construct(
        public int|string $id,
        public MCPErrorCode $code,
        public string $message,
        public ?array $data = null,
    ) {}


    public function toValue(): array
    {
        return [
            'code' => $this->code->value,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    public function toJsonRPC(): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->id,
            'error' => $this->toValue(),
        ];
    }
}
