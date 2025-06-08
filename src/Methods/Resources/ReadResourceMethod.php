<?php

namespace Superconductor\Methods\Resources;

use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Resources\ReadResourceRequest as V20241105ReadResourceRequest;
use Superconductor\Schema\Definitions\V20250326\Resources\ReadResourceRequest as V20250326ReadResourceRequest;
use Superconductor\Support\Facades\MCPCapabilities;

class ReadResourceMethod
{
    public function handle(V20241105ReadResourceRequest|V20250326ReadResourceRequest $incoming_message): Result
    {
        $results = MCPCapabilities::dispatch('resources', $incoming_message);

        return $incoming_message->toResult(...$results);
    }
}
