<?php

namespace Superconductor\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Superconductor\Managers\MCPSessionManager;


/**
 * @method static driver(?string $name = null)
 * @method static createOrLoad(?string $session_id = null, ?string $driver = null)
 *
 * @see MCPSessionManager
 */
class SessionManager extends Facade
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
