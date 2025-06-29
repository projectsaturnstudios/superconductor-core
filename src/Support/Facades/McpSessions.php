<?php

namespace Superconductor\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Superconductor\Managers\Sessions\StatefulSessionManager;
use Superconductor\MCPClient;
use Superconductor\MCPServer;
use Superconductor\StatefulMCPSession;

/**
 * @method static save(StatefulMCPSession $session, ?string $driver = null)
 * @method static load(string $session_id, ?string $driver = null)
 *
 * @see StatefulSessionManager
 */
class McpSessions extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mcp-sessions';
    }
}
