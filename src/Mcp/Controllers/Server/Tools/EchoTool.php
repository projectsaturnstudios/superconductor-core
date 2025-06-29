<?php

namespace Superconductor\Mcp\Controllers\Server\Tools;

use JSONRPC\Exceptions\RPCResponseException;
use Superconductor\Messages\ResponseMessage;
use Superconductor\Support\Attributes\MCPServerCapability;
use Superconductor\Support\Attributes\ServerTool;

#[ServerTool(
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
#[MCPServerCapability('echo')]
class EchoTool extends BaseToolCall
{
    /**
     * @param array $params
     * @return ResponseMessage
     * @throws RPCResponseException
     */
    public function handle(array $params): ResponseMessage
    {
        $response = $this->execute(...$params['arguments']);
        return new ResponseMessage(
            id: 0,
            result: [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $response
                    ]
                ]
            ]
        );
    }

    private function execute(string $intended_output) : string
    {
        return $intended_output;
    }
}
