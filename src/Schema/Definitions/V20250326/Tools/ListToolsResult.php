<?php

namespace Superconductor\Schema\Definitions\V20250326\Tools;


use Superconductor\Schema\Definitions\V20250326\Pagination\PaginatedResult;

class ListToolsResult extends PaginatedResult
{
    public function __construct(
        public string|int $id,
        public array $tools = [],
        public ?string $nextCursor = null,
    ) {
        parent::__construct(
            id: $id,
        );
    }

    protected function getResultData(): array
    {
        return [
            'tools' => $this->tools,
        ];
    }
}
