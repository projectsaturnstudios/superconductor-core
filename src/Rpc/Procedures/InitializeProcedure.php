<?php

namespace Superconductor\Mcp\Rpc\Procedures;

use Superconductor\Mcp\Servers\MCPServer;
use Superconductor\Rpc\Rpc\Procedures\RpcProcedure;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\Support\Attributes\UsesRpcRequest;
use Superconductor\Mcp\DTO\Messages\Requests\InitializeRequest;

#[UsesRpcRequest(InitializeRequest::class)]
class InitializeProcedure extends RpcProcedure
{
    public function handle(InitializeRequest $request): RpcResult|RpcError
    {
        /** @var MCPServer|null $server */
        $server = $request->getAdditionalData()['server'] ?? null;
        if($server) $server->setClientCapabilities($request->getCapabilities());

        $protocol_version = default_protocol_version();

        $capabilities = [];
        if($server) $capabilities = $server->getServerCapabilities();

        $server_info = config('superconductor.server_info', [
            'name' => 'Superconductor MCP Server',
            'version' => '20241105.5.0'
        ]);
        if($server) $server_info = $server->getServerInfo();

        return (new RpcResult($request->id, [
            'protocolVersion' => $protocol_version,
            'capabilities' => $capabilities,
            'serverInfo' => $server_info,
        ]))->additional(['server' => $server]);

    }
}
