<?php

namespace Superconductor\Schema\Definitions\V20250326\Maps;

use Superconductor\Schema\Definitions\V20250326\Base\Notification;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCNotification;
use Superconductor\Schema\Definitions\V20250326\Protocol\InitializedNotification;

class NotificationUnion
{
    protected static array $schema = [
        'notifications/initialized' => InitializedNotification::class,
    ];

    public static function create(JSONRPCNotification $request): Notification
    {
        $class = self::$schema[$request->method];
        return $class::convert($request);
    }
}
