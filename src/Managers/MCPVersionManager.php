<?php

namespace Superconductor\Managers;

use Illuminate\Support\Manager;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Drivers\Versions\SchemaDriver;
use Superconductor\Drivers\Versions\SchemaDriverV20241105;
use Superconductor\Drivers\Versions\SchemaDriverV20250326;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCResponse;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCNotification;

class MCPVersionManager extends Manager
{
    public function error(string|int $id, MCPErrorCode $code, string $message, ?array $data = null, ?string $driver = null): JSONRPCError
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->error($id, $code, $message, $data);
    }

    public function notification(string $method, ?array $params = null, ?string $driver = null): JSONRPCNotification
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->notification($method, $params);
    }

    public function result(string|int $id, array $result, ?string $driver = null): JSONRPCResponse
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->result($id, $result);
    }

    public function request(string|int $id, string $method, ?array $params = null, ?string $driver = null): JSONRPCRequest
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->request($id, $method, $params);
    }

    public function createV20241105Driver(): SchemaDriver
    {
        return new SchemaDriverV20241105();
    }

    public function createV20250326Driver(): SchemaDriver
    {
        return new SchemaDriverV20250326();
    }

    public function getDefaultDriver() : string
    {
        $active_version = str_replace('-', '',
            config('mcp.versions.active', '2024-11-05')
        );

        return "V{$active_version}";
    }

    public static function boot(): void
    {
        app()->singleton('mcp-versions', function ($app) {
            $manager = new static($app);

            $add_ons = config('mcp.mcp-versions.add-ons', []);
            foreach($add_ons as $slug => $add_on) {
                $manager->extend($slug, fn() => new $add_on['driver_class']($add_on));
            }

            return $manager;
        });
    }
}
