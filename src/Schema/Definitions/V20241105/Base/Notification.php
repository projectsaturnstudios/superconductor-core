<?php

namespace Superconductor\Schema\Definitions\V20241105\Base;

use Superconductor\Schema\MCPSchema;

class Notification extends MCPSchema
{

    public function __construct(
        public ?string $method = null,
        public ?array $params = null,
        public ?array $_meta = null,
    ) {}

    public function toValue() : array
    {
        $results = [
            'method' => $this->method,
        ];

        if ($this->params !== null) {
            $results['params'] = $this->params;
        }

        if ($this->_meta !== null) {
            $results['params']['_meta'] = $this->_meta;
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
