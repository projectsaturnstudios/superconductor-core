<?php

namespace Superconductor\Methods;

use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Protocol\InitializeRequest as V20241105InitializeRequest;
use Superconductor\Schema\Definitions\V20250326\Protocol\InitializeRequest as V20250326InitializeRequest;

class InitializeMethod
{
    public function handle(V20241105InitializeRequest|V20250326InitializeRequest $incoming_message): Result
    {


        return $incoming_message->toResult(
            protocolVersion: $incoming_message->params['protocolVersion'],
            capabilities: [
                'resources' => [
                    'listChanged' => false
                ],
                'tools' => [
                    'listChanged' => false
                ],
                'prompts' => [
                    'listChanged' => false
                ]
            ],
            serverInfo: config('mcp.server.details')
        );
    }
}
