<?php

namespace Superconductor\Mcp\Clients;

use Illuminate\Container\Container;

use Illuminate\Container\Attributes\Singleton;

#[Singleton]
class ClientRegistrar
{
    protected array $peers = [];

    public function __construct(
        protected Container $app,
    ) {}

    public function register(string $method, string $action): ClientRoute
    {
        $peer = new ClientRoute($method, $action, $this);

        $this->peers[$method] = $peer;
        return $peer;
    }

    public function getClients(): array
    {
        return $this->peers;
    }

    public static function boot(): void
    {
        app()->singleton('mcp-clients', fn(Container $app) => new static($app));
    }
}
