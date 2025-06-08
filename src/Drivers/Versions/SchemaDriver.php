<?php

namespace Superconductor\Drivers\Versions;

use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCNotification;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCResponse;

abstract class SchemaDriver
{
    abstract public function error(int|string $id, MCPErrorCode $code, string $message, ?array $data = null): JSONRPCError;
    abstract public function result(int|string $id, array $result): JSONRPCResponse;
    abstract public function request(int|string $id, string $method, array $params): JSONRPCRequest;
    abstract public function notification(string $method, array $params): JSONRPCNotification;
}
