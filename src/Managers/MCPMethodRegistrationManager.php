<?php

namespace Superconductor\Managers;

use Illuminate\Support\Manager;
use Superconductor\Drivers\Features\Methods\HardcodedMethodRegistrar;
use Superconductor\Drivers\Features\Methods\MethodRegistrar;
use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Base\Request;
use Superconductor\Schema\Definitions\V20241105\Base\Notification;

class MCPMethodRegistrationManager extends Manager
{
    public function dispatch(Request|Notification $incoming_message, ?string $schema_version = null, ?string $driver = null): Result
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->dispatch($incoming_message, $schema_version);
    }

    public function createHardcodedDriver(): MethodRegistrar
    {
        return new HardcodedMethodRegistrar();
    }

    public function getDefaultDriver() : string
    {
        return config('mcp.features.methods.registrar.default', 'hardcoded');
    }

    public static function boot(): void
    {
        app()->singleton('mcp-methods', function ($app) {
            $manager = new static($app);

            $add_ons = config('mcp.features.methods.registrar.add-ons', []);
            foreach($add_ons as $slug => $add_on) {
                $manager->extend($slug, fn() => new $add_on['driver_class']($add_on));
            }

            return $manager;
        });
    }
}
