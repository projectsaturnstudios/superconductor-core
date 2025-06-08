<?php

namespace Superconductor\Schema\Definitions\V20241105\Maps;

use Superconductor\Schema\Definitions\V20241105\Base\Notification;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCNotification;
use Superconductor\Schema\Definitions\V20241105\Protocol\InitializedNotification;

class NotificationUnion
{
    protected static array $schema = [
        'notifications/initialized' => InitializedNotification::class,
    ];

    public static function create(JSONRPCNotification $request): Notification
    {
        return new $request->method(...$request->toValue());
    }
}
