<?php

namespace Superconductor\Mcp\Clients;

use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcNotification;
use Superconductor\Rpc\DTO\Messages\RpcMessage;
use Superconductor\Rpc\Rpc\Procedures\RpcProcedure;

class ClientRoute
{
    public function __construct(
        public readonly string       $method,
        public readonly string       $class_name,
        protected ClientRegistrar &$registrar
    )
    {}


}
