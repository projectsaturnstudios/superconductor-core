<?php

namespace Superconductor\Mcp\Controllers\Server;

use Superconductor\Support\Attributes\MCPServerCapability;

abstract class McpServerController
{
    public static function _getMCPServerCapabilityAttribute(): ?MCPServerCapability
    {
        $attribute = (new \ReflectionClass(new static))->getAttributes(MCPServerCapability::class);
        return $attribute[0]->newInstance();
    }

    public function getMCPServerCapabilityAttribute(): ?MCPServerCapability
    {
        return self::_getMCPServerCapabilityAttribute();
    }

    public function getCapabilityRoute(): string
    {
        $attribute = $this->getMCPServerCapabilityAttribute();
        if ($attribute === null) {
            throw new \RuntimeException('MCPServerCapability attribute not found.');
        }
        return $attribute->route;
    }

    public static function _getCapabilityRoute(): string
    {
        $attribute = self::_getMCPServerCapabilityAttribute();
        if ($attribute === null) {
            throw new \RuntimeException('MCPServerCapability attribute not found.');
        }
        return $attribute->route;
    }

    public function isAccessible(): bool
    {
        $attribute = $this->getMCPServerCapabilityAttribute();
        if ($attribute === null) {
            throw new \RuntimeException('MCPServerCapability attribute not found.');
        }
        return $attribute->is_accessible();
    }

    public static function _isAccessible(): bool
    {
        $attribute = self::_getMCPServerCapabilityAttribute();
        if ($attribute === null) {
            throw new \RuntimeException('MCPServerCapability attribute not found.');
        }
        return $attribute->is_accessible();
    }
}
