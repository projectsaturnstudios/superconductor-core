<?php

namespace Superconductor\Rpc\Controllers;

use JSONRPC\RPCResponse;
use JSONRPC\RPCNotification;
use JSONRPC\Attributes\MethodController;
use JSONRPC\Rpc\Controllers\RpcController;
use JSONRPC\Exceptions\RPCResponseException;

#[MethodController('notifications')]
class NotificationsController extends RpcController
{
    public function middleware(): array
    {
        return [];
    }

    /**
     * @param RPCNotification $request
     * @return RPCResponse
     * @throws RPCResponseException
     */
    public function initialized(RPCNotification $request): RPCResponse
    {
        return new RPCResponse($request->method, 0, []);
    }

    public function cancelled(RPCNotification $request): RPCResponse
    {
        // @todo - there may be business logic to be done here.
        return new RPCResponse($request->method, 0, []);
    }
}
