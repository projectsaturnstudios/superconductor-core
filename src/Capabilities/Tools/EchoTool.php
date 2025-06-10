<?php

namespace Superconductor\Capabilities\Tools;

use Superconductor\Support\Attributes\ToolCall;
use Superconductor\Schema\Definitions\V20241105\Tools\CallToolRequest as V20241105CallToolRequest;
use Superconductor\Schema\Definitions\V20250326\Tools\CallToolRequest as V20250326CallToolRequest;

#[ToolCall(
    tool: 'echo',
    description: 'Echoes back the request data for testing purposes',
    inputSchema: [
        'type'   => 'object',
        'properties' => [
            'intended_output' => [
                'type'        => 'string',
                'description' => 'The intended output of the echo.'
            ]
        ],
        'required' => ['intended_output']
    ]
)]
class EchoTool extends BaseTool
{
    public function handle(V20241105CallToolRequest|V20250326CallToolRequest $incoming_message): array
    {
        $results = [
            'content' => [],
            'isError' => false
        ];

        if($content = $this->execute(...$incoming_message->params['arguments']))
        {
            $text_content_class = $incoming_message::getTextContextObj();
            $text_content = new $text_content_class($content);
            $results['content'][] = $text_content->toValue();
        }

        return $results;
    }

    private function execute(string $intended_output) : string
    {
        return $intended_output;
    }
}
