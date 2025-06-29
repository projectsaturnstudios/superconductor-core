<?php

namespace Superconductor\Actions\Flow;

use ProjectSaturnStudios\PocketFlow\Node;
use Superconductor\ServerCarrier;
use Superconductor\StatefulMCPSession;
use Superconductor\Support\Facades\McpSessions;

class MCPStartNode extends Node
{
    protected ?string $next_action = null;

    public function prep(mixed &$shared): mixed
    {
        /** @var ServerCarrier $shared */
        // Here we need to load or Create the MCP session
        $session = McpSessions::load($shared->server->session_id());
        if (!$session) {
            $session = new StatefulMCPSession($shared->server->session_id());
        }
        $shared->session = $session->save();
        logger()->log('info', 'MCPSession ready.', [
            'session_id' => $shared->session->session_id,

        ]);
        return $shared;
    }

    public function exec(mixed $prep_res): mixed
    {

        return ['success' => true, 'message' => 'MCP Start Node executed successfully.'];
    }

    public function post(mixed &$shared, mixed $prep_res, mixed $exec_res): mixed
    {
        logger()->log('info', 'MCP Start Node completed with action: ' . $this->next_action);
        return $this->next_action ? $this->next_action : 'finished';
    }

    public function setNextAction(string $action): static
    {
        $this->next_action = $action;
        return $this;
    }
}
