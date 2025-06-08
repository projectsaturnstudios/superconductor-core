<?php

namespace Superconductor\Managers;

use Illuminate\Support\Manager;
use Superconductor\Schema\Definitions\V20241105\Base\Request;
use Superconductor\Schema\Definitions\V20241105\Base\Notification;
use Superconductor\Drivers\Features\Capabilities\CapabilityRegistrar;
use Superconductor\Drivers\Features\Capabilities\HardcodedCapabilityRegistrar;

class MCPCapabilityRegistrationManager extends Manager
{
    public function list_capabilities(string $capability, ?string $schema_version = null, ?string $driver = null): array
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->list_capabilities($capability, $schema_version);
    }

    public function dispatch(string $capability, Request|Notification $incoming_message, ?string $driver = null): array
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->dispatch($capability, $incoming_message);
    }

    public function createHardcodedDriver(): CapabilityRegistrar
    {
        return new HardcodedCapabilityRegistrar();
    }

    public function getDefaultDriver() : string
    {
        return config('mcp.features.capabilities.registrar.default', 'hardcoded');
    }

    public static function boot(): void
    {
        app()->singleton('mcp-capabilities', function ($app) {
            $manager = new static($app);

            $add_ons = config('mcp.features.capabilities.registrar.add-ons', []);
            foreach($add_ons as $slug => $add_on) {
                $manager->extend($slug, fn() => new $add_on['driver_class']($add_on));
            }

            return $manager;
        });
    }
}
