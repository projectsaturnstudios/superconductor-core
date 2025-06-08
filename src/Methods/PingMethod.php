<?php

namespace Superconductor\Methods;

use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20241105\Protocol\PingRequest as V20241105PingRequest;
use Superconductor\Schema\Definitions\V20250326\Protocol\PingRequest as V20250326PingRequest;

class PingMethod
{
    public function handle(V20241105PingRequest|V20250326PingRequest $incoming_message): Result
    {
        // @todo - load the session and save the client's capabilities

        return $incoming_message->toResult();
    }
}
