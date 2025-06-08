<?php

namespace Superconductor\Contracts\Sessions;

use Superconductor\Sessions\AbstractMCPSession;

interface MCPSessionDriverInterface
{
    public function save(AbstractMCPSession $session);
    public function load(string $session_id);
}
