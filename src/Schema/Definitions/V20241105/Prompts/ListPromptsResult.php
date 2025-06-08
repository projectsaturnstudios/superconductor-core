<?php

namespace Superconductor\Schema\Definitions\V20241105\Prompts;

use Superconductor\Schema\Definitions\V20241105\Pagination\PaginatedResult;

class ListPromptsResult extends PaginatedResult
{
    public function __construct(
        string|int $id,
        public array $prompts = [],
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
            'prompts' => $this->prompts,
        ];
    }
}
