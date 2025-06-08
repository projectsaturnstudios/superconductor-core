<?php

namespace Superconductor\Schema\Definitions\V20250326\Resources;

use Superconductor\Schema\Definitions\V20250326\Base\Request;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20250326\Content\TextResourceContents;

class ReadResourceRequest extends Request
{
    public function __construct(
        string|int $id,
        public string $uri,
    ) {
        $params = [
            'uri' => $this->uri,
        ];


        parent::__construct(
            id: $id,
            method: 'resources/read',
            params: $params
        );
    }

    public static function convert(JSONRPCRequest $data): static
    {
        return new self(
            id: $data->id,
            uri: $data->params['uri']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uri' => $this->uri,
        ];
    }

    public function toResult(
        array $contents = []
    ): ReadResourceResult
    {

        return new ReadResourceResult(
            id: $this->id,
            contents: $contents
        );
    }

    public static function getTextResourceContextObj(): string
    {
        return TextResourceContents::class;
    }
}
