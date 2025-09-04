<?php

namespace Superconductor\Mcp\DTO\Messages\Notifications;

use Superconductor\Rpc\DTO\Messages\Incoming\RpcNotification;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;

class LogMessageNotification extends RpcNotification
{
    public function __construct(
        public readonly string $level,
        public readonly string $logger,
        public readonly array $data = [],

    ) {
        parent::__construct(method: 'notifications/message', params: [
            'level' => $level,
            'logger' => $logger,
            'data' => $data,
        ]);
    }
}
