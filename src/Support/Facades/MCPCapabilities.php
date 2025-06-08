<?php

namespace Superconductor\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Superconductor\Managers\MCPCapabilityRegistrationManager;
use Superconductor\Managers\MCPMethodRegistrationManager;
use Superconductor\Schema\Definitions\V20241105\Base\Request;
use Superconductor\Schema\Definitions\V20241105\Base\Notification;


/**
 * @method static driver(?string $name = null)
 * @method static dispatch(string $capability, Request|Notification $message, ?string $driver = null)
 * @method static list_capabilities(string $capability, ?string $schema_version = null, ?string $driver = null)
 *
 * @see MCPCapabilityRegistrationManager
 */
class MCPCapabilities extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mcp-capabilities';
    }
}
