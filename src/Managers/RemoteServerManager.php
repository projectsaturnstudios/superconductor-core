<?php

namespace MCP\Managers;

use Illuminate\Support\Manager;

class RemoteServerManager extends Manager
{
    public function getDefaultDriver() : null
    {
        return null;
    }

    public static function boot(): void
    {
        app()->singleton('remote_server_manager', function ($app) {
            $results = new static($app);

            $add_ons = config('mcp.transports');
            foreach($add_ons as $driver => $add_on)
            {
                if(array_key_exists('client_manager', $add_on))
                {
                    $client_manager = $add_on['client_manager'];
                    $results->extend($driver, fn() => new $client_manager($add_on));
                }
            }

            return $results;
        });
    }
}
