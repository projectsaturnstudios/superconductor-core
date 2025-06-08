<?php

namespace Superconductor\Schema\Definitions\V20241105\Tools;

use Superconductor\Schema\Definitions\V20241105\Pagination\PaginatedResult;

class ListToolsResult extends PaginatedResult
{
    public function __construct(
        string|int $id,
        public array $tools = [],
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
            'tools' => $this->tools,
        ];
    }
}
