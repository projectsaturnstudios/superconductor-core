<?php

namespace Superconductor\Mcp\Rpc\Procedures;

use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\Rpc\Procedures\RpcProcedure;
use Superconductor\Mcp\DTO\Messages\Requests\PingRequest;
use Superconductor\Rpc\Support\Attributes\UsesRpcRequest;

#[UsesRpcRequest(PingRequest::class)]
class PingProcedure extends RpcProcedure
{
    public function handle(PingRequest $request): RpcResult|RpcError
    {
        return new RpcResult($request->id, []);
    }
}
