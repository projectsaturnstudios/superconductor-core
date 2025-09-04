<?php

namespace Superconductor\Mcp\DTO\Messages\Requests;

use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;

class InitializeRequest extends RpcRequest
{
    public function __construct(
        int $id,
        public readonly string $protocolVersion,
        public readonly array $capabilities,
        public readonly array $clientInfo,
    ) {
        parent::__construct(id: $id, method: 'initialize', params: [
            'protocolVersion' => $protocolVersion,
            'capabilities' => $capabilities,
            'clientInfo' => $clientInfo,
        ]);
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    public function getClientInfo(): array
    {
        return $this->clientInfo;
    }

    public static function fromRpcRequest(RpcRequest $request): RpcRequest
    {
        return new self(
            $request->id,
            ...$request->params
        );
    }
}
