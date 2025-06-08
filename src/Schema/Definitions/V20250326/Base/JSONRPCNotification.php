<?php

namespace Superconductor\Schema\Definitions\V20250326\Base;

use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCNotification as PreviousVersion;
use Superconductor\Schema\Definitions\V20250326\Maps\NotificationUnion;

class JSONRPCNotification extends PreviousVersion
{
    public function convertToMCP(): Notification
    {
        return NotificationUnion::create($this);
    }
}
