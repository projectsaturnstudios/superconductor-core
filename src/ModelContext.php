<?php

namespace Superconductor;



use Illuminate\Foundation\Auth\User;
use JSONRPC\RPCResponse;
use JSONRPC\Support\Facades\RPCRouter;
use Superconductor\Drivers\Transports\TransportProtocolDriver;
use Superconductor\Messages\RequestMessage;
use Superconductor\Messages\ResponseMessage;

class ModelContext
{
    public static function protocol(string $transport_protocol): TransportProtocolDriver
    {
        return app('transport_protocols')->driver($transport_protocol);
    }

    public static function serve(string $transport, string|int $session_id, ?User $user = null): McpServer
    {
        $payload = [
            'transport_protocol' => $transport,
            'session_id' => $session_id,
        ];

        if ($user) {
            $payload['user'] = $user;
        }

        return app(MCPServer::class, $payload);
    }

    public static function client(): MCPClient
    {
        $client = new MCPClient();

        return $client;
    }

    public static function host(): MCPHost
    {
        $host = new MCPHost();

        return $host;
    }

    public static function boot(): void
    {
        app()->singleton('mcp', function () {
            return new static();
        });
    }

    public static function method(RequestMessage $message): ResponseMessage
    {
        $resp = RPCRouter::dispatch($message);
        if($resp instanceof RPCResponse) $resp = ResponseMessage::from($resp);
        return $resp;
    }
}
