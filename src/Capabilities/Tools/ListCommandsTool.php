<?php

namespace Superconductor\Capabilities\Tools;

use Illuminate\Support\Facades\Artisan;
use Superconductor\Schema\Definitions\V20241105\Tools\CallToolRequest as V20241105CallToolRequest;
use Superconductor\Schema\Definitions\V20250326\Tools\CallToolRequest as V20250326CallToolRequest;
use Superconductor\Support\Attributes\ToolCall;

#[ToolCall(
    tool: 'list_commands',
    description: 'Lists all available commands in the system',
    inputSchema: [
        'type'   => 'object'
    ]
)]
class ListCommandsTool extends BaseTool
{
    public function handle(V20241105CallToolRequest|V20250326CallToolRequest $incoming_message): array
    {
        $results = [
            'content' => [],
            'isError' => false
        ];

        $text_content_class = $incoming_message::getTextContextObj();
        $text_content = new $text_content_class("Available Commands");
        $results['content'][] = $text_content->toValue();

        foreach(Artisan::all() as $slug => $command) {
            $text_content = new $text_content_class("$slug - {$command->getDescription()}");
            $results['content'][] = $text_content->toValue();
        }

        return $results;
    }
}
