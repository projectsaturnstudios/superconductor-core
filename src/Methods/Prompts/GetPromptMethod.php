<?php

namespace Superconductor\Methods\Prompts;

use Superconductor\Support\Facades\MCPCapabilities;
use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Prompts\GetPromptRequest as V20241105GetPromptRequest;
use Superconductor\Schema\Definitions\V20250326\Prompts\GetPromptRequest as V20250326GetPromptRequest;

class GetPromptMethod
{
    public function handle(V20241105GetPromptRequest|V20250326GetPromptRequest $incoming_message): Result
    {
        $results = MCPCapabilities::dispatch('prompts', $incoming_message);

        return $incoming_message->toResult(...$results);
    }
}
