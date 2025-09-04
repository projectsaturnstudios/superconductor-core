<?php

namespace Superconductor\Mcp\DTO\Messages\Requests;

use Superconductor\Mcp\DTO\References\PromptReference;
use Superconductor\Mcp\DTO\References\ReferenceObject;
use Superconductor\Mcp\DTO\References\ResourceReference;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;

class LoggingRequest extends RpcRequest
{
    public function __construct(
        int $id,
        public readonly string $level,

    ) {
        parent::__construct(id: $id, method: 'logging/setLevel', params: [
            'level' => $level,
        ]);
    }

    public static function fromRpcRequest(RpcRequest $request): RpcRequest
    {
        return new self(
            $request->id,
            ...$request->params
        );
    }
}
