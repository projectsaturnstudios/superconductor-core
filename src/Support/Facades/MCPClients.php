<?php

namespace Superconductor\Mcp\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Superconductor\Mcp\Clients\ClientRoute;

/**
 * @method static ClientRoute register(string $method, string $action)
 * @see \Superconductor\Mcp\Clients\ClientRegistrar
 */
class MCPClients extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'mcp-clients';
    }
}
