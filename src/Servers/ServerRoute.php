<?php

namespace Superconductor\Mcp\Servers;

use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcNotification;
use Superconductor\Rpc\DTO\Messages\RpcMessage;
use Superconductor\Rpc\Rpc\Procedures\RpcProcedure;

class ServerRoute
{
    public function __construct(
        public readonly string       $method,
        public readonly string       $class_name,
        protected ServerRegistrar &$registrar
    )
    {}


}
