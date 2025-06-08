<?php

namespace Superconductor\Managers;

use Illuminate\Support\Manager;

class MCPLoopManager extends Manager
{
    public function getDefaultDriver() : string
    {
        return config('mcp.looping_mechanism.default', 'blocking');
    }

    public static function boot(): void
    {
        app()->singleton('mcp-loops', function ($app) {
            $manager = new static($app);

            $add_ons = config('mcp.looping_mechanism.add-ons', []);
            foreach($add_ons as $slug => $add_on) {
                $manager->extend($slug, fn() => new $add_on['driver_class']($add_on));
            }

            return $manager;
        });
    }
}
