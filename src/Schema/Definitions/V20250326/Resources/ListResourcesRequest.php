<?php

namespace Superconductor\Schema\Definitions\V20250326\Resources;

use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20250326\Pagination\PaginatedRequest;

class ListResourcesRequest extends PaginatedRequest
{
    public function __construct(
        string|int $id,
        ?string $cursor = null,
    ) {
        parent::__construct(
            id: $id,
            method: 'resources/list',
            cursor: $cursor
        );
    }

    public static function convert(JSONRPCRequest $data): static
    {
        return new self(
            id: $data->id,
            cursor: $data->params['cursor'] ?? null
        );
    }

    public function toResult(
        array $resources = [],
        ?string $nextCursor = null
    ): ListResourcesResult
    {
        return new ListResourcesResult(
            id: $this->id,
            resources: $resources,
            nextCursor: $nextCursor
        );
    }
}
