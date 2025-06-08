<?php

namespace Superconductor\Schema\Definitions\V20250326\ResourceTemplates;

use Superconductor\Schema\Definitions\V20250326\Pagination\PaginatedResult;

class ListResourceTemplatesResult extends PaginatedResult
{
    public function __construct(
        string|int $id,
        public array $resourceTemplates = [],
        ?string $nextCursor = null,
        ?array $_meta = null,
    ) {
        parent::__construct(
            id: $id,
            nextCursor: $nextCursor,
            _meta: $_meta
        );
    }

    protected function getResultData(): array
    {
        return [
            'resourceTemplates' => $this->resourceTemplates,
        ];
    }
}
