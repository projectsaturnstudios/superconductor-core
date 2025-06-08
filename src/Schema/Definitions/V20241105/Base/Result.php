<?php

namespace Superconductor\Schema\Definitions\V20241105\Base;

use Superconductor\Schema\MCPSchema;

class Result extends MCPSchema
{

    public function __construct(
        public ?array $_meta = null,
    ) {}

    public function toValue() : array
    {
        $results = [];

        if ($this->_meta != null) {
            $results['_meta'] = $this->_meta;
        }

        return $results;
    }

    public function toJsonRPC(): array
    {
        return [
            'jsonrpc' => '2.0',
            ...$this->toValue(),
        ];
    }
}
