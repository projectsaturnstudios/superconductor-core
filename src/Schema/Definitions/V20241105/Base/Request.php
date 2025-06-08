<?php

namespace Superconductor\Schema\Definitions\V20241105\Base;

use Superconductor\Schema\MCPSchema;

class Request extends MCPSchema
{
    public function __construct(
        public readonly string|int $id,
        public readonly string $method,
        public ?array $params = null,
        public ?array $_meta = null,
        public null|string|int $progressToken = null,
    ) {

    }


    public function toValue(): array
    {
        $results = [
            'method' => $this->method,
        ];

        if ($this->params !== null) {
            $results['params'] = $this->params;
        }

        if ($this->_meta !== null) {
            $results['params']['_meta'] = $this->_meta;

            if ($this->progressToken !== null) {
                $results['params']['_meta']['progressToken'] = $this->progressToken;
            }
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
