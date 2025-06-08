<?php

namespace Superconductor\Schema\Definitions\V20250326\Prompts;

use Superconductor\Schema\Definitions\V20250326\Base\Request;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20250326\Content\TextContent;

class GetPromptRequest extends Request
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
            method: 'prompts/get',
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
        array $messages = [],
        ?string $description = null
    ): GetPromptResult
    {
        return new GetPromptResult(
            id: $this->id,
            messages: $messages,
            description: $description
        );
    }

    public static function getTextContextObj(): string
    {
        return TextContent::class;
    }
}
