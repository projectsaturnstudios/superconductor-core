<?php

namespace Superconductor\Drivers\Sessions;

use Superconductor\Sessions\AbstractMCPSession;
use Superconductor\Contracts\Sessions\MCPSessionDriverInterface;

abstract class SessionDriver implements MCPSessionDriverInterface
{
    public function __construct() {}
    abstract public function save(AbstractMCPSession $session): AbstractMCPSession;
    abstract public function load(string $session_id): ?AbstractMCPSession;
}
