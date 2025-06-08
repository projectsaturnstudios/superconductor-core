<?php

namespace Superconductor\Drivers\Features\Methods;

use Superconductor\Schema\Definitions\V20241105\Base\Notification;
use Superconductor\Schema\Definitions\V20241105\Base\Request;
use Superconductor\Schema\Definitions\V20241105\Base\Result;

class HardcodedMethodRegistrar extends MethodRegistrar
{
    public function dispatch(Request|Notification $message, ?string $schema_version = null): Result
    {
        $method = $message->method;
        $method_class = config("mcp.features.methods.registrar.available.hardcoded.{$method}");
        return (new $method_class())->handle($message, $schema_version);
    }
}
