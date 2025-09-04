<?php

namespace Superconductor\Mcp\Servers;

use Illuminate\Container\Container;

use Superconductor\Mcp\Servers\ServerRoute;
use Superconductor\Rpc\Enums\RPCErrorCode;
use Illuminate\Container\Attributes\Singleton;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcNotification;

#[Singleton]
class ServerRegistrar
{
    protected array $peers = [];

    public function __construct(
        protected Container $app,
    ) {}

    public function register(string $method, string $action): ServerRoute
    {
        $peer = new ServerRoute($method, $action, $this);

        $this->peers[$method] = $peer;
        return $peer;
    }

    public function getServers(): array
    {
        return $this->peers;
    }

    public static function boot(): void
    {
        app()->singleton('mcp-servers', fn(Container $app) => new static($app));
    }
}
