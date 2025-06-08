<?php

namespace Superconductor\Capabilities\Tools;

use ReflectionClass;
use Superconductor\Support\Attributes\ToolCall;
use Superconductor\Schema\Definitions\V20241105\Tools\Tool as V20241105Tool;
use Superconductor\Schema\Definitions\V20250326\Tools\Tool as V20250326Tool;

abstract class BaseTool
{
    public static function _getToolCallAttribute() : ?ToolCall
    {
        $attribute = (new ReflectionClass(new static))->getAttributes(ToolCall::class);
        return $attribute[0]->newInstance();
    }

    public function getToolCallAttribute() : ?ToolCall
    {
        return self::_getToolCallAttribute();
    }

    public static function getToolInfo(?string $protocolVersion = null): V20241105Tool|V20250326Tool
    {
        /** @var ToolCall $attr */
        return (self::_getToolCallAttribute())->toTool($protocolVersion);
    }

    public static function getToolListEntry(string $protocolVersion) : array
    {
        return (static::getToolInfo($protocolVersion))->toValue();
    }
}
