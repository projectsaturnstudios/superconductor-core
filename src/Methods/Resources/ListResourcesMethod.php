<?php

namespace Superconductor\Methods\Resources;

use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Resources\ListResourcesRequest as V20241105ListResourcesRequest;
use Superconductor\Schema\Definitions\V20241105\Resources\Resource;
use Superconductor\Schema\Definitions\V20250326\Resources\ListResourcesRequest as V20250326ListResourcesRequest;

class ListResourcesMethod
{
    public function handle(
        V20241105ListResourcesRequest|V20250326ListResourcesRequest $incoming_message,
        ?string $schema_version = null
    ): Result
    {
        $resources = array_map(fn(Resource $resource) => $resource->toValue(), list_resources($schema_version));
        return $incoming_message->toResult(
            resources: $resources,
        );
    }
}
