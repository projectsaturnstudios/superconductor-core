<?php

namespace Superconductor\Support\Facades;

use Superconductor\Enums\MCPErrorCode;
use Illuminate\Support\Facades\Facade;
use Superconductor\Managers\MCPVersionManager;


/**
 * @method static driver(?string $name = null)
 * @method static array error(string|int $id, MCPErrorCode $code, string $message, ?array $data = null)
 * @method static array notification(string $method, ?array $params = null, ?string $driver = null)
 * @method static array result(string|int $id, array $result, ?string $driver = null)
 * @method static array request(string|int $id, string $method, ?array $params = null, ?string $driver = null)
 *
 * @see MCPVersionManager
 */
class SchemaManager extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mcp-versions';
    }
}
