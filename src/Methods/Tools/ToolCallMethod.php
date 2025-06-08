<?php

namespace Superconductor\Methods\Tools;

use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Tools\CallToolRequest as V20241105CallToolRequest;
use Superconductor\Schema\Definitions\V20250326\Tools\CallToolRequest as V20250326CallToolRequest;
use Superconductor\Support\Facades\MCPCapabilities;

class ToolCallMethod
{
    public function handle(V20241105CallToolRequest|V20250326CallToolRequest $incoming_message): Result
    {
        $results = MCPCapabilities::dispatch('tools', $incoming_message);

        return $incoming_message->toResult(...$results);
    }
}
