<?php

namespace Superconductor\Mcp\Rpc\Procedures;

use Superconductor\Mcp\Servers\MCPServer;
use Superconductor\Rpc\Rpc\Procedures\RpcProcedure;
use Superconductor\Rpc\Support\Attributes\UsesRpcRequest;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcNotification;

#[UsesRpcRequest(RpcNotification::class)]
class NotificationsProcedure extends RpcProcedure
{
    public function progress(RpcNotification $notification): bool
    {
        return true;
    }

    public function cancelled(RpcNotification $notification): bool
    {
        return true;
    }

    public function initialized(RpcNotification $notification): bool
    {
        /** @var MCPServer|null $server */
        $server = $notification->getAdditionalData()['server'] ?? null;
        if($server) $server = $server->setClientReady();

        return true;
    }
}
