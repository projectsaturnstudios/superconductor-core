<?php

namespace Superconductor\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Superconductor\MCPClient;
use Superconductor\MCPServer;
use Superconductor\Messages\RequestMessage;

/**
 * @method static client()
 * @method static host()
 * @method static serve(string $transport_protocol, string|int $session_id, \Illuminate\Foundation\Auth\User|null $user = null)
 * @method static protocol(string $transport_protocol)
 * @method static method(RequestMessage $message)
 *
 * @see \Superconductor\ModelContext
 */
class Mcp extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mcp';
    }
}
