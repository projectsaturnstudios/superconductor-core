<?php

namespace Superconductor\Schema\Definitions\V20241105\Prompts;

use Superconductor\Schema\Definitions\V20241105\Pagination\PaginatedRequest;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Tools\ListToolsResult;

class ListPromptsRequest extends PaginatedRequest
{
    public function __construct(
        string|int $id,
        ?string $cursor = null,
    ) {
        parent::__construct(
            id: $id,
            method: 'prompts/list',
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
        array $prompts = [],
        ?string $nextCursor = null
    ): ListPromptsResult
    {
        return new ListPromptsResult(
            id: $this->id,
            prompts: $prompts,
            nextCursor: $nextCursor
        );
    }
}
