<?php

namespace Superconductor\Schema\Definitions\V20250326\Roots;

use Superconductor\Schema\Definitions\V20250326\Pagination\PaginatedResult;

class ListRootsResult extends PaginatedResult
{
    public function __construct(
        string|int $id,
        public array $roots = [],
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
            'roots' => $this->roots,
        ];
    }
}
