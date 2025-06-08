<?php

namespace Superconductor\Schema\Definitions\V20241105\Resources;

use Superconductor\Schema\Definitions\V20241105\Base\Result;

class ReadResourceResult extends Result
{
    public function __construct(
        public string|int $id,
        public array $contents = [],
    ) {
        parent::__construct();
    }

    public function toValue(): array
    {
        $results = parent::toValue();

        $results['id'] = $this->id;
        $results['result'] = [
            'contents' => $this->contents,
        ];

        return $results;
    }
}
