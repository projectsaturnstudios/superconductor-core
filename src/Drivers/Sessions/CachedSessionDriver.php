<?php

namespace Superconductor\Drivers\Sessions;

use Illuminate\Support\Facades\Cache;
use Superconductor\Sessions\AbstractMCPSession;

class CachedSessionDriver extends SessionDriver
{
    public function save(AbstractMCPSession $session): AbstractMCPSession
    {
        Cache::put(
            "mcp-session-{$session->session_id}",
            $session,
            now()->addMinutes(config('mcp.session_managers.available.cached.sessions_expire_in', 10))
        );

        return $session;
    }

    public function load(string $session_id): ?AbstractMCPSession
    {
        return Cache::get("mcp-session-{$session_id}");
    }
}
