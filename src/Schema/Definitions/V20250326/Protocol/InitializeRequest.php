<?php

namespace Superconductor\Schema\Definitions\V20250326\Protocol;

use Superconductor\Schema\Definitions\V20250326\Base\Request;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;

class InitializeRequest extends Request
{
    public function __construct(
        string|int $id,
        string $protocolVersion,
        array $capabilities = [],
        array $clientInfo = [],
    ) {
        parent::__construct(
            id: $id,
            method: 'initialize',
            params: [
            'protocolVersion' => $protocolVersion,
            'capabilities' => $capabilities,
            'clientInfo' => $clientInfo,
        ]);
    }

    public static function convert(JSONRPCRequest $data): static
    {
        return new self(
            id: $data->id,
            protocolVersion: $data->params['protocolVersion'],
            capabilities: $data->params['capabilities'],
            clientInfo: $data->params['clientInfo']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'protocolVersion' => $this->params['protocolVersion'],
            'capabilities' => $this->params['capabilities'],
            'clientInfo' => $this->params['clientInfo'],
        ];
    }

    public function toResult(
        string $protocolVersion,
        array $capabilities,
        array $serverInfo,
        ?string $instructions = null,
    ): InitializeResult
    {
        return new InitializeResult(
            id: $this->id,
            protocolVersion: $protocolVersion,
            capabilities: $capabilities,
            serverInfo: $serverInfo,
            instructions: $instructions
        );
    }

}
