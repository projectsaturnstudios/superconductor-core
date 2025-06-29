<?php

namespace Superconductor\Managers\Transports;

use Illuminate\Support\Manager;

class TransportProtocolManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return '';
    }

    public static function boot(): void
    {
        app()->singleton('transport_protocols', function ($app) {
            $results = new static($app);

            $protocols = config('mcp.transports', []);
            foreach($protocols as $protocol => $config) {
                if($config['enabled'])
                {
                    $driver_slug = $config['driver'];
                    $config = $config['drivers'][$driver_slug];
                    $results->extend($protocol, fn() => new $config['class']($config));
                }
            }

            return $results;
        });
    }
}
