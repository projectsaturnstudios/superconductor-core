<?php

namespace Superconductor\Managers;

use Illuminate\Support\Manager;
use Superconductor\Sessions\MCPSessionObject;
use Superconductor\Sessions\AbstractMCPSession;
use Superconductor\Drivers\Sessions\SessionDriver;
use Superconductor\Drivers\Sessions\CachedSessionDriver;
use Superconductor\Drivers\Sessions\DatabaseSessionDriver;

class MCPSessionManager extends Manager
{
    public function createOrLoad(?string $session_id = null, ?string $driver = null): AbstractMCPSession
    {
        $driver = $driver ?? $this->getDefaultDriver();
        if($session_id) {
            $session = $this->driver($driver)->load($session_id);
            if(empty($session)) throw new \DomainException('Session not found');
            return $session;
        }

        $session_class = config('mcp.sessions.session_object_class', MCPSessionObject::class);
        return new $session_class();
    }

    public function createDatabaseDriver(): SessionDriver
    {
        return new DatabaseSessionDriver();
    }

    public function createCachedDriver(): SessionDriver
    {
        return new CachedSessionDriver();
    }

    public function getDefaultDriver() : string
    {
        return config('mcp.session_managers.default', 'cached');
    }

    public static function boot(): void
    {
        app()->singleton('mcp-sessions', function ($app) {
            $manager = new static($app);

            $add_ons = config('mcp.session_managers.add-ons', []);
            foreach($add_ons as $slug => $add_on) {
                $manager->extend($slug, fn() => new $add_on['driver_class']($add_on));
            }

            return $manager;
        });
    }
}
