<?php

namespace Superconductor\Mcp\DTO\Messages\Requests;

use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;

class PingRequest extends RpcRequest
{
    public function __construct(
        int $id,
    ) {
        parent::__construct(id: $id, method: 'ping');
    }

    public static function fromRpcRequest(RpcRequest $request): RpcRequest
    {
        return new self($request->id);
    }
}
