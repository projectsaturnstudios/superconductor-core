<?php

namespace Superconductor\Schema\Definitions\V20241105\Tools;

use Superconductor\Schema\Definitions\V20241105\Pagination\PaginatedRequest;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;

class ListToolsRequest extends PaginatedRequest
{
    public function __construct(
        string|int $id,
        ?string $cursor = null,
    ) {
        parent::__construct(
            id: $id,
            method: 'tools/list',
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
        array $tools = [],
        ?string $nextCursor = null
    ): ListToolsResult
    {
        return new ListToolsResult(
            id: $this->id,
            tools: $tools,
            nextCursor: $nextCursor
        );
    }
}
