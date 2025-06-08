<?php

namespace Superconductor\Capabilities\Resources;

use ReflectionClass;
use Superconductor\Schema\Definitions\V20241105\Resources\Resource as V20241105Resource;
use Superconductor\Schema\Definitions\V20250326\Resources\Resource as V20250326Resource;
use Superconductor\Support\Attributes\ReadableResource;
use Superconductor\Support\Attributes\ToolCall;

abstract class BaseResource
{
    public static function _getResourceAttribute() : ?ReadableResource
    {
        $attribute = (new ReflectionClass(new static))->getAttributes(ReadableResource::class);
        return $attribute[0]->newInstance();
    }

    public function getResourceAttribute() : ?ReadableResource
    {
        return self::_getResourceAttribute();
    }

    public static function getResourceInfo(?string $protocolVersion = null): V20241105Resource|V20250326Resource
    {
        /** @var ToolCall $attr */
        return (self::_getResourceAttribute())->toResource($protocolVersion);
    }

    public static function getResourceListEntry(string $protocolVersion) : array
    {
        return (static::getResourceInfo($protocolVersion))->toValue();
    }
}
