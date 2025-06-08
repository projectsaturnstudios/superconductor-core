<?php

namespace Superconductor\Schema\Definitions\V20250326\Protocol;

use Superconductor\Schema\Definitions\V20250326\Base\Request;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;

class PingRequest extends Request
{
    public function __construct(
        string|int $id,
    )
    {
        parent::__construct(
            id: $id,
            method: 'ping'
        );
    }

    public static function convert(JSONRPCRequest $data): static
    {
        return new self(
            id: $data->id,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function toResult(): PingResult
    {
        return new PingResult(
            id: $this->id
        );
    }
}
