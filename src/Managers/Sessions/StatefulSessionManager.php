<?php

namespace Superconductor\Managers\Sessions;

use Illuminate\Support\Manager;
use Superconductor\Drivers\Sessions\CachedMCPSessionDriver;
use Superconductor\StatefulMCPSession;

class StatefulSessionManager extends Manager
{
    public function save(StatefulMCPSession $session, ?string $driver = null): StatefulMCPSession
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->save($session);
    }

    public function load(string|int $session_id, ?string $driver = null): ?StatefulMCPSession
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->load($session_id);
    }


    public function createCachedDriver(): CachedMCPSessionDriver
    {
        return new CachedMCPSessionDriver();
    }

    public function getDefaultDriver(): string
    {
        return  config('mcp.sessions.default', 'cached');
    }

    public static function boot(): void
    {
        app()->singleton('mcp-sessions', function ($app) {;
            $results = new static($app);

            return $results;
        });
    }
}
