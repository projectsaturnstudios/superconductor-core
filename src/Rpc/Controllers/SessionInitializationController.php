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
use Superconductor\Messages\SuccessfulResponseMessage;
use Superconductor\StatefulMCPSession;
use Superconductor\Support\Facades\McpSessions;

#[MethodController('initialize')]
class SessionInitializationController extends RpcController
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
    public function handle(RequestMessage $message): RPCResponse
    {
        /** @var StatefulMCPSession $session */
        $session = McpSessions::load($message->getAdditionalData()['session_id']);
        if(!$session) return new ErrorResponseMessage($message->method, $message->id, new RPCErrorObject(RPCErrorCode::INTERNAL_ERROR, 'Session not found'));
        $capabilities = $message->params['capabilities'] ?? [];
        foreach($capabilities as $capability => $details)
        {
            $session = $session->addClientCapability($capability, $details);
        }

        $payload = [
            'protocolVersion' => '2024-11-05',
            'capabilities' => [
                "tools"=> [
                    "listChanged" => true
                ]
            ],
            'serverInfo' => config('mcp.server_info', [])
        ];

        $session = McpSessions::load($message->getAdditionalData()['session_id']);
        return new SuccessfulResponseMessage($message->id, $payload);

        /**
         * STEPS
         * 1. Protocol version will be 2024-11-05
         * 2. Build out the capabilities detection and ecosystem
         */
        //{
        //    "jsonrpc": "2.0",
        //    "id": 1,
        //    "result": {
        //        "protocolVersion": "2024-11-05",
        //        "capabilities": {
        //            "logging": {},
        //            "prompts": {
        //                "listChanged": true
        //            },
        //            "resources": {
        //                "subscribe": true,
        //                "listChanged": true
        //            },
        //            "tools": {
        //                "listChanged": true
        //            }
        //        },
        //        "serverInfo": {
        //            "name": "ExampleServer",
        //            "version": "1.0.0"
        //        }
        //    }
        //}
    }
}
