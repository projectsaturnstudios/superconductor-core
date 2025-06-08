<?php

namespace Superconductor\Schema\Definitions\V20250326\Pagination;

use Superconductor\Schema\Definitions\V20250326\Base\Request;

abstract class PaginatedRequest extends Request
{
    /**
     * @param string|int $id The request ID
     * @param string $method The method name for the request
     * @param string|null $cursor An opaque token representing the current pagination position
     */
    public function __construct(
        string|int $id,
        string $method,
        public ?string $cursor = null,
    ) {
        $params = [];

        if ($this->cursor !== null) {
            $params['cursor'] = $this->cursor;
        }

        parent::__construct(
            id: $id,
            method: $method,
            params: !empty($params) ? $params : null
        );
    }

    public function toValue(): array
    {
        $results = parent::toValue();

        if ($this->cursor !== null) {
            if (!isset($results['params'])) {
                $results['params'] = [];
            }

            $results['params']['cursor'] = $this->cursor;
        }

        return $results;
    }
}
