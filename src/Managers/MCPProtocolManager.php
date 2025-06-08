<?php

namespace Superconductor\Managers;

use Illuminate\Support\Manager;

class MCPProtocolManager extends Manager
{
    public function createNoneDriver(): null
    {
        return null;
    }

    public function getDefaultDriver() : string
    {
        return 'none';
    }

    public static function boot(): void
    {
        app()->singleton('mcp-protocols', function ($app) {
            $manager = new static($app);

            $add_ons = config('mcp.protocols', []);
            foreach($add_ons as $slug => $add_on) {
                $manager->extend($slug, fn() => new $add_on['driver_class']($add_on));
            }

            return $manager;
        });
    }
}
