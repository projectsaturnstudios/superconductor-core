<?php

namespace Superconductor\Mcp\Controllers\Server\Tools;

use Superconductor\Support\Attributes\ServerTool;
use Superconductor\Mcp\Controllers\Server\McpServerController;

abstract class BaseToolCall extends McpServerController
{
    public static function _getToolCallAttribute() : ?ServerTool
    {
        $attribute = (new \ReflectionClass(new static))->getAttributes(ServerTool::class);
        return $attribute[0]->newInstance();
    }

    public function getToolCallAttribute() : ?ServerTool
    {
        return self::_getToolCallAttribute();
    }

    public static function getToolInfo(): array
    {
        /** @var ServerTool $attr */
        return (self::_getToolCallAttribute())->toTool();
    }

    public static function getToolListEntry() : array
    {
        return (self::_getToolCallAttribute())->toValue();
    }
}
