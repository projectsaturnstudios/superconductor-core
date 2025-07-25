<?php

namespace  MCP\Support\Facades;

use MCP\Sessions\ClientSession;
use MCP\Managers\RemoteServerManager;
use Illuminate\Support\Facades\Facade;
use MCP\Drivers\ClientConnectionDriver;

/**
 * @method static ClientConnectionDriver|null driver(?string $name = null)
 * @method static array list_tools(ClientSession $session, array $connection)
 *
 * @see RemoteServerManager
 */
class MCPClientManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'remote_server_manager';
    }
}
