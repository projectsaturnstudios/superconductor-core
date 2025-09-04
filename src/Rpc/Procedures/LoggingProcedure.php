<?php

namespace Superconductor\Mcp\Rpc\Procedures;

use Illuminate\Support\Facades\Event;
use Superconductor\Mcp\DTO\Messages\Notifications\LogMessageNotification;
use Superconductor\Rpc\Rpc\Procedures\RpcProcedure;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\Support\Attributes\UsesRpcRequest;
use Superconductor\Mcp\DTO\Messages\Requests\LoggingRequest;

#[UsesRpcRequest(LoggingRequest::class)]
class LoggingProcedure extends RpcProcedure
{
    public function handle(LoggingRequest $request): RpcResult|RpcError
    {
        $channel = $request->getAdditionalData()['notification_channel'] ?? null;
        if($channel) {
            $notification = new LogMessageNotification(
                level: $request->level,
                logger: 'system',
                data: [
                    'message' => "Log level changed to {$request->level}",
                ]
            );
            Event::dispatch($channel, ['notification'=> $notification]);
        }

        return new RpcResult($request->id, []);
    }
}
