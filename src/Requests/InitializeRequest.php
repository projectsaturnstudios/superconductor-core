<?php

namespace MCP\Requests;

use JSONRPC\RPCRequest;

class InitializeRequest extends RPCRequest
{
    public function __construct(
        string $protocolVersion,
        array $capabilities = [],
        array $clientInfo = [],
        string|int|null $id = null
    ) {
        parent::__construct('initialize', [
            'protocolVersion' => $protocolVersion,
            'capabilities' => $capabilities,
            'clientInfo' => $clientInfo
        ], $id);
    }
}
