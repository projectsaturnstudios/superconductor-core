<?php

namespace Superconductor\Mcp\Controllers\Server\Tools;

use Superconductor\Messages\ResponseMessage;
use Superconductor\Support\Attributes\MCPServerCapability;
use Superconductor\Support\Attributes\ServerTool;

#[ServerTool(
    tool: 'list_commands',
    description: 'Lists all available commands in the system',
    inputSchema: [
        'type'   => 'object'
    ]
)]
#[MCPServerCapability('list_commands')]
class ListCommandsTool extends BaseToolCall
{
    public function handle(): ResponseMessage
    {

    }

    private function execute(string $intended_output) : string
    {
        return $intended_output;
    }
}
