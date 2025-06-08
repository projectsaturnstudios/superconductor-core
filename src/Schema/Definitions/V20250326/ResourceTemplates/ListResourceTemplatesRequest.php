<?php

namespace Superconductor\Schema\Definitions\V20250326\ResourceTemplates;

use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20250326\Pagination\PaginatedRequest;

class ListResourceTemplatesRequest extends PaginatedRequest
{
    public function __construct(
        string|int $id,
        ?string $cursor = null,
    ) {
        parent::__construct(
            id: $id,
            method: 'resources/templates/list',
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
        array $resourceTemplates = [],
        ?string $nextCursor = null
    ): ListResourceTemplatesResult
    {
        return new ListResourceTemplatesResult(
            id: $this->id,
            resourceTemplates: $resourceTemplates,
            nextCursor: $nextCursor
        );
    }
}
