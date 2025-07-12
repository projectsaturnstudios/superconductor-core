<?php

namespace MCP\Rpc\Controllers;

use JSONRPC\RPCRequest;
use JSONRPC\RPCResponse;
use MCP\Requests\InitializeRequest;
use JSONRPC\Attributes\MethodController;
use Lorisleiva\Actions\Concerns\AsAction;
use JSONRPC\Rpc\Controllers\RpcController;
use JSONRPC\Exceptions\RPCResponseException;

#[MethodController('initialize')]
class InitializeController extends RpcController
{
    use AsAction;

    /**
     * @param InitializeRequest $request
     * @return RPCResponse
     * @throws RPCResponseException
     */
    public function handle(InitializeRequest $request): RPCResponse
    {
        // @todo - something needs to be done to remember the capabilities passed in by the client
        $result = new RPCResponse(
            $request->id,
            [
                'protocolVersion' => config('mcp.versions.default', '2024-11-05'),
                'capabilities' => $this->list_capabilities(),
                'serverInfo' => config('mcp.server_info', ['name' => 'Superconductor', 'version' => '1.0.0',])
            ]
        );

        return $result;
    }

    private function list_capabilities(): array
    {
        $results = [];

        $caps = config('mcp.capabilities.server', []);
        foreach($caps as $cap => $config)
        {
            if($config['enabled']) $results[$cap] = $config['init'] ?? [];
        }

        return $results;
    }

    public function makeRequest(array $message): InitializeRequest
    {
        $payload = [
            'id' => $message['id'],
            ...$message['params']
        ];
        return InitializeRequest::from($payload);
    }
}
