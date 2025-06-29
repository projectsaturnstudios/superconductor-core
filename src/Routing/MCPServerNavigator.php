<?php

namespace Superconductor\Routing;

use Superconductor\Mcp\Controllers\Server\McpServerController;
use Superconductor\Messages\NotificationMessage;
use Superconductor\Messages\RequestMessage;
use Superconductor\Messages\ResponseMessage;

class MCPServerNavigator
{
    protected array $tools = [];
    protected array $resources = [];
    protected array $prompts = [];
    protected array $experimental = [];

    public function addCapability(McpServerController $controller, string $capability_group): static
    {
        if($controller::_isAccessible()) ($this->$capability_group)[$controller::_getCapabilityRoute()] = $controller;
        return $this;
    }

    public function dispatch(string $capability_group, string $tool, array $payload): ResponseMessage
    {
        $class = ($this->$capability_group)[$tool];
        return (new $class())->handle($payload);
    }

    public function getCapabilities(string $capability_group): array
    {
        return $this->$capability_group;
    }

    public static function boot(): void
    {
        app()->singleton('mcp.server-router', static fn () => new static());
    }

    public function __call(string $method, array $args): mixed
    {
        if($args[0] instanceof RequestMessage || $args[0] instanceof NotificationMessage) {


        }
        else
        {
            throw new \BadMethodCallException("Capability $method does not exist or is not callable with the provided arguments.");
        }
    }
}


