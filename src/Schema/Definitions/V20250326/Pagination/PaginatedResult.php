<?php

namespace Superconductor\Schema\Definitions\V20250326\Pagination;

use Superconductor\Schema\Definitions\V20250326\Base\Result;

abstract class PaginatedResult extends Result
{
    /**
     * @param string|int $id The result ID
     * @param string|null $nextCursor An opaque token representing the pagination position after the last returned result
     * @param array|null $_meta Additional metadata for the response
     */
    public function __construct(
        public string|int $id,
        public ?string $nextCursor = null,
        ?array $_meta = null,
    ) {
        parent::__construct($_meta);
    }

    public function toValue(): array
    {
        $results = parent::toValue();

        $results['id'] = $this->id;
        $results['result'] = $this->getResultData();

        if ($this->nextCursor !== null) {
            $results['result']['nextCursor'] = $this->nextCursor;
        }

        return $results;
    }
}
