<?php

namespace MCP\Requests;

use JSONRPC\RPCRequest;

class PingRequest extends RPCRequest
{
    public function __construct(
        string|int|null $id
    ) {
        parent::__construct('ping', [], $id);
    }
}
