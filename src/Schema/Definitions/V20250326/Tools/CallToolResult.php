<?php

namespace Superconductor\Schema\Definitions\V20250326\Tools;

use Superconductor\Schema\Definitions\V20250326\Base\Result;

class CallToolResult extends Result
{
    public function __construct(
        public string|int $id,
        public array $content = [],
        public bool $isError = false,
    ) {
        parent::__construct();
    }

    public function toValue(): array
    {
        $results = parent::toValue();

        $results['id'] = $this->id;
        $results['result'] = [
            'content' => $this->content,
        ];

        if ($this->isError === true) {
            $results['result']['isError'] = $this->isError;
        }

        return $results;
    }
} 