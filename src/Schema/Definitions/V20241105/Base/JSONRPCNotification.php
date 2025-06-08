<?php

namespace Superconductor\Schema\Definitions\V20241105\Base;

use Superconductor\Schema\MCPSchema;
use Superconductor\Schema\Definitions\V20241105\Maps\NotificationUnion;

class JSONRPCNotification extends MCPSchema
{
    public static string $type = 'notification';
    public function __construct(
        public readonly string $method,
        public readonly mixed $params = null,
    ) {}


    public function toValue(): array
    {
        return $this->params;
    }

    public function toJsonRPC(): array
    {
        return [
            'jsonrpc' => '2.0',
            'method' => $this->method,
            'message' => $this->toValue(),
        ];
    }

    public function convertToMCP(): Notification
    {
        return NotificationUnion::create($this);
    }
}
