<?php

namespace Superconductor\Schema\Definitions\V20241105\Protocol;

use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Base\Request;

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
