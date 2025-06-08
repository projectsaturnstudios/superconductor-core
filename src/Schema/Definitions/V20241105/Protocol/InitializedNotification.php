<?php

namespace Superconductor\Schema\Definitions\V20241105\Protocol;

use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCNotification;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Base\Notification;

class InitializedNotification extends Notification
{
    public function __construct()
    {
        parent::__construct('notifications/initialized');
    }

    public function toValue(): array
    {
        return [
            'method' => $this->method,
        ];
    }

    public static function convert(JSONRPCNotification $data): static
    {
        return new self();
    }
}
