<?php

namespace Superconductor\Drivers\Sessions;

use Superconductor\StatefulMCPSession;

abstract class StatefulMCPSessionDriver
{
    public function save(StatefulMCPSession $session): StatefulMCPSession
    {
        return $session;
    }

    public function load(string|int $session_id): ?StatefulMCPSession
    {
        return null;
    }
}
