<?php

namespace Superconductor\Schema\Definitions\V20241105\Roots;

use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Pagination\PaginatedRequest;

class ListRootsRequest extends PaginatedRequest
{
    public function __construct(
        string|int $id,
        ?string $cursor = null,
    ) {
        parent::__construct(
            id: $id,
            method: 'roots/list',
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
        array $roots = [],
        ?string $nextCursor = null
    ): ListRootsResult
    {
        return new ListRootsResult(
            id: $this->id,
            roots: $roots,
            nextCursor: $nextCursor
        );
    }
}
