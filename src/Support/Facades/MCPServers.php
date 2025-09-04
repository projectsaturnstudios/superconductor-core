<?php

namespace Superconductor\Mcp\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Superconductor\Mcp\Servers\ServerRoute;

/**
 * @method static ServerRoute register(string $method, string $action)
 * @see \Superconductor\Mcp\Servers\ServerRegistrar
 */
class MCPServers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'mcp-servers';
    }
}
