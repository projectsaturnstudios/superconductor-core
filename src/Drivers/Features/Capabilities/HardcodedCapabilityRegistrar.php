<?php

namespace Superconductor\Drivers\Features\Capabilities;

use Superconductor\Drivers\Features\Methods\MethodRegistrar;
use Superconductor\Schema\Definitions\V20241105\Base\Notification;
use Superconductor\Schema\Definitions\V20241105\Base\Request;
use Superconductor\Schema\Definitions\V20241105\Base\Result;

class HardcodedCapabilityRegistrar extends CapabilityRegistrar
{
    public function list_capabilities(string $capability, ?string $schema_version = null): array
    {
        $results = [];

        $capabilities = config("mcp.features.capabilities.registrar.available.hardcoded.{$capability}");
        foreach($capabilities as $name => $class_name)
        {
            switch($capability)
            {
                case 'tools':
                    $results[$name] = $class_name::getToolInfo($schema_version);
                    break;

                case 'resources':
                    $results[$name] = $class_name::getResourceInfo($schema_version);
                    break;

                case 'prompts':
                    $results[$name] = $class_name::getPromptInfo($schema_version);
                    break;
            }

        }

        return $results;
    }

    public function dispatch(string $capability, Request|Notification $message): array
    {
        switch($capability)
        {
            case 'tools':
            case 'prompts':
                $method_class = config("mcp.features.capabilities.registrar.available.hardcoded.{$capability}.{$message->params['name']}");
                break;

            case 'resources':
                $method_class = config("mcp.features.capabilities.registrar.available.hardcoded.{$capability}")[$message->params['uri']];
                break;
        }

        return (new $method_class())->handle($message);
    }
}
