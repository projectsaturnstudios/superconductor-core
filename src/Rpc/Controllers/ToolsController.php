<?php

namespace Superconductor\Rpc\Controllers;

use JSONRPC\Enums\RPCErrorCode;
use JSONRPC\Exceptions\RPCResponseException;
use JSONRPC\RPCErrorObject;
use JSONRPC\RPCResponse;
use JSONRPC\Attributes\MethodController;
use JSONRPC\Rpc\Controllers\RpcController;
use Superconductor\Messages\ErrorResponseMessage;
use Superconductor\Messages\RequestMessage;
use Superconductor\Messages\ResponseMessage;
use Superconductor\Messages\SuccessfulResponseMessage;
use Superconductor\StatefulMCPSession;
use Superconductor\Support\Facades\McpRouter;
use Superconductor\Support\Facades\McpSessions;

#[MethodController('tools')]
class ToolsController extends RpcController
{
    public function middleware(): array
    {
        return [];
    }

    /**
     * @param RequestMessage $message
     * @return RPCResponse
     * @throws RPCResponseException
     */
    public function list(RequestMessage $message): RPCResponse
    {
        // @todo - add a filter for ACL, that hasn't been built yet
        $tools_list = array_map(function($tool) {
            return $tool::getToolListEntry();
        }, McpRouter::getCapabilities('tools'));
        return new ResponseMessage(
            id: $message?->id,
            result: [
                'tools' => array_values($tools_list)
            ]
        );


    }

    /**
     * @param RequestMessage $message
     * @return RPCResponse
     * @throws RPCResponseException
     */
    public function call(RequestMessage $message): RPCResponse
    {
        $tool = $message->params['name'];
        $response = McpRouter::dispatch('tools', $tool, $message->params);
        return new SuccessfulResponseMessage(
            id: $message->id,
            result: $response->result
        );

    }
}
