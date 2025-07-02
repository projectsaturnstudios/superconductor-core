<?php

namespace MCP\Contracts;

use MCP\Capabilities\CapabilityRegistrar;

interface CapabilityRegistrarContract
{
    public static function setCapability(CapabilityRegistrar &$registrar,string $name, string $class): void;
}
