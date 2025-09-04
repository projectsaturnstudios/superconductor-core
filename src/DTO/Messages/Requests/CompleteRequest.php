<?php

namespace Superconductor\Mcp\DTO\Messages\Requests;

use Superconductor\Mcp\DTO\References\PromptReference;
use Superconductor\Mcp\DTO\References\ReferenceObject;
use Superconductor\Mcp\DTO\References\ResourceReference;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;

class CompleteRequest extends RpcRequest
{
    public function __construct(
        int $id,
        public readonly ReferenceObject $ref,
        public readonly array $argument,

    ) {
        parent::__construct(id: $id, method: 'completion/complete', params: [
            'ref' => $ref,
            'argument' => $argument,
        ]);
    }

    public static function fromRpcRequest(RpcRequest $request): RpcRequest
    {
        $params = $request->params ?? [];
        $ref = null;
        if(isset($params['ref']['name'])) $ref = new PromptReference($params['ref']['name']);
        elseif(isset($params['ref']['uri'])) $ref = new ResourceReference($params['ref']['uri']);
        $argument = [];
        if(isset($params['argument']['name'])) $argument['name'] = $params['argument']['name'];
        if(isset($params['argument']['value'])) $argument['value'] = $params['argument']['value'];

        return new self($request->id, $ref, $argument);
    }
}
