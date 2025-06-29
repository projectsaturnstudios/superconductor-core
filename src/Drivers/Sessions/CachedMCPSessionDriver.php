<?php

namespace Superconductor\Drivers\Sessions;

use Illuminate\Support\Facades\Cache;
use Superconductor\StatefulMCPSession;

class CachedMCPSessionDriver extends StatefulMCPSessionDriver
{
    public function save(StatefulMCPSession $session): StatefulMCPSession
    {
        Cache::put("mcp_session:{$session->session_id}", $session);
        return $session;
    }

    public function load(string|int $session_id): ?StatefulMCPSession
    {
        return Cache::get("mcp_session:{$session_id}", null);
    }
}
