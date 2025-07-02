<?php

namespace MCP\Capabilities\RequestRouting;

use MCP\Capabilities\CapabilityRegistrar;
use MCP\Contracts\CapabilityRegistrarContract;

abstract class CapabilityRegistrationService implements CapabilityRegistrarContract
{
    abstract public static function setCapability(CapabilityRegistrar &$registrar, string $name, string $class): void;
}
