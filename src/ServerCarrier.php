<?php

namespace Superconductor;

use Spatie\LaravelData\Data;

class ServerCarrier extends Data
{
    public function __construct(
        public readonly MCPServer $server,
        public bool $stop_loop = false,
        public ?StatefulMCPSession $session = null,
        public ?array $raw_messages = null
    ) {}
}
