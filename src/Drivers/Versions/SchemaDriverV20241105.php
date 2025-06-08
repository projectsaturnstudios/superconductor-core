<?php

namespace Superconductor\Drivers\Versions;

use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCResponse;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCNotification;

class SchemaDriverV20241105 extends SchemaDriver
{
    public function error(int|string $id, MCPErrorCode $code, string $message, ?array $data = null): JSONRPCError
    {
        return new JSONRPCError(
            id: $id,
            code: $code,
            message: $message,
            data: $data
        );
    }

    public function notification(string $method, ?array $params = null): JSONRPCNotification
    {
        return new JSONRPCNotification(
            method: $method,
            params: $params
        );
    }

    public function result(int|string $id, array $result): JSONRPCResponse
    {
        return new JSONRPCResponse(
            id: $id,
            result: $result
        );
    }

    public function request(int|string $id, string $method, ?array $params = null): JSONRPCRequest
    {
        return new JSONRPCRequest(
            id: $id,
            method: $method,
            params: $params
        );
    }
}
