<?php

namespace Superconductor\Managers;

use Illuminate\Support\Manager;

class MCPResponseDispatchManager extends Manager
{
    public function getDefaultDriver() : string
    {
        return config('mcp.outgoing_message_dispatcher.default', 'cached');
    }

    public static function boot(): void
    {
        app()->singleton('mcp-responses', function ($app) {
            $manager = new static($app);

            $add_ons = config('mcp.outgoing_message_dispatcher.add-ons', []);
            foreach($add_ons as $slug => $add_on) {
                $manager->extend($slug, fn() => new $add_on['driver_class']($add_on));
            }

            return $manager;
        });
    }
}
