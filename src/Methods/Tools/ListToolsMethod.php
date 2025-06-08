<?php

namespace Superconductor\Methods\Tools;

use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Tools\ListToolsRequest as V20241105ListToolsRequest;
use Superconductor\Schema\Definitions\V20241105\Tools\Tool;
use Superconductor\Schema\Definitions\V20250326\Tools\ListToolsRequest as V20250326ListToolsRequest;

class ListToolsMethod
{
    public function handle(
        V20241105ListToolsRequest|V20250326ListToolsRequest $incoming_message,
        ?string $schema_version = null
    ): Result
    {
        $tools = array_map(fn(Tool $tool) => $tool->toValue(), list_tools($schema_version));
        return $incoming_message->toResult(
            tools: $tools,
        );
    }
}
