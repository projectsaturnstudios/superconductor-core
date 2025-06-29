<?php

namespace Superconductor\Drivers\Transports;

use Superconductor\Contracts\ModelContextTransportProtocol;
use Superconductor\MCPServer;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class TransportProtocolDriver implements ModelContextTransportProtocol
{
    abstract public function start(MCPServer $server): StreamedResponse|int;
    abstract public function send(array $message): void;
}
