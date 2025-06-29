<?php

namespace Superconductor\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Superconductor\Mcp\Controllers\Server\McpServerController;
use Superconductor\MCPClient;
use Superconductor\MCPServer;
use Superconductor\Messages\RequestMessage;

/**
 * @method static MCPServer addCapability(McpServerController $controller, string $capability_group)
 * @method static array getCapabilities(string $capability_group = null)
 *
 * @see \Superconductor\Routing\MCPServerNavigator
 */
class McpRouter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mcp.server-router';
    }
}
