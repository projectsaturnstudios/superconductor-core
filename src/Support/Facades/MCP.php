<?php

namespace  MCP\Support\Facades;

use Illuminate\Support\Facades\Facade;
use MCP\Capabilities\CapabilityExecution\CapabilityRequest;
use MCP\Capabilities\CapabilityExecution\CapabilityResult;
use MCP\ModelContextProtocol;

/**
 * @method static ModelContextProtocol addCapability(string $capability, string $protocol, ?bool $is_experimental = false)
 * @method static CapabilityResult action(string $protocol, string $capability, string $action, CapabilityRequest $request, bool $is_experimental = false)
 * @method static array capabilities(string $protocol)
 * @method static array capability_routes(string $protocol, string $capability_group, ?string $subgroup = null)
 *
 * @see ModelContextProtocol
 */
class MCP extends Facade
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
