<?php

namespace Superconductor\Schema\Definitions\V20241105\Tools;

use Superconductor\Schema\Definitions\V20241105\Base\Request;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Content\TextContent;

class CallToolRequest extends Request
{
    public function __construct(
        string|int $id,
        public string $name,
        public ?array $arguments = null,
    ) {
        $params = [
            'name' => $this->name,
        ];

        if ($this->arguments !== null) {
            $params['arguments'] = $this->arguments;
        }

        parent::__construct(
            id: $id,
            method: 'tools/call',
            params: $params
        );
    }

    public static function convert(JSONRPCRequest $data): static
    {
        return new self(
            id: $data->id,
            name: $data->params['name'],
            arguments: $data->params['arguments'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'arguments' => $this->arguments,
        ];
    }

    public function toResult(
        array $content = [],
        bool $isError = false
    ): CallToolResult
    {
        return new CallToolResult(
            id: $this->id,
            content: $content,
            isError: $isError
        );
    }

    public static function getTextContextObj(): string
    {
        return TextContent::class;
    }
}
