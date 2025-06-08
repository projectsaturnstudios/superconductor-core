<?php

namespace Superconductor\Schema\Definitions\V20241105\Resources;

use Superconductor\Schema\Definitions\V20241105\Pagination\PaginatedResult;

class ListResourcesResult extends PaginatedResult
{
    public function __construct(
        string|int $id,
        public array $resources = [],
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
            'resources' => $this->resources,
        ];
    }
}
