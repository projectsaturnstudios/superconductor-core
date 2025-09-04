<?php

namespace Superconductor\Mcp\Rpc\Procedures;

use Superconductor\Rpc\Rpc\Procedures\RpcProcedure;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\Support\Attributes\UsesRpcRequest;
use Superconductor\Mcp\DTO\Messages\Requests\CompleteRequest;

#[UsesRpcRequest(CompleteRequest::class)]
class CompletionsProcedure extends RpcProcedure
{
    public function handle(CompleteRequest $request): RpcResult|RpcError
    {
        return new RpcResult($request->id, [
            'completion' => [
                'values' => [],
                'total' => 0,
                'hasMore' => false,
            ]
        ]);
    }
}
