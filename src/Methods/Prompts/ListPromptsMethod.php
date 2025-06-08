<?php

namespace Superconductor\Methods\Prompts;

use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Prompts\ListPromptsRequest as V20241105ListPromptsRequest;
use Superconductor\Schema\Definitions\V20241105\Prompts\Prompt;
use Superconductor\Schema\Definitions\V20250326\Prompts\ListPromptsRequest as V20250326ListPromptsRequest;

class ListPromptsMethod
{
    public function handle(
        V20241105ListPromptsRequest|V20250326ListPromptsRequest $incoming_message,
        ?string $schema_version = null
    ): Result
    {
        $prompts = array_map(fn(Prompt $prompts) => $prompts->toValue(), list_prompts($schema_version));

        return $incoming_message->toResult(
            prompts: $prompts,
        );
    }
}
