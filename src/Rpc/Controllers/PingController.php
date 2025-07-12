<?php

namespace MCP\Rpc\Controllers;

use JSONRPC\RPCResponse;
use MCP\Requests\PingRequest;
use JSONRPC\Attributes\MethodController;
use Lorisleiva\Actions\Concerns\AsAction;
use JSONRPC\Rpc\Controllers\RpcController;
use JSONRPC\Exceptions\RPCResponseException;

#[MethodController('ping')]
class PingController extends RpcController
{
    use AsAction;

    /**
     * @param PingRequest $request
     * @return RPCResponse
     * @throws RPCResponseException
     */
    public function handle(PingRequest $request): RPCResponse
    {
        return new RPCResponse(
            $request->id,
            new \stdClass()
        );
    }

    public function makeRequest(array $message): PingRequest
    {
        return new PingRequest($message['id']);
    }
}
