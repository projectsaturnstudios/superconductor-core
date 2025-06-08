<?php
namespace Superconductor\Capabilities\Prompts;

use ReflectionClass;
use Superconductor\Schema\Definitions\V20241105\Prompts\Prompt as V20241105Prompt;
use Superconductor\Schema\Definitions\V20250326\Prompts\Prompt as V20250326Prompts;
use Superconductor\Support\Attributes\ActionablePrompt;

abstract class BasePrompt
{
    public static function _getPromptAttribute() : ?ActionablePrompt
    {
        $attribute = (new ReflectionClass(new static))->getAttributes(ActionablePrompt::class);
        return $attribute[0]->newInstance();
    }

    public function getPromptAttribute() : ?ActionablePrompt
    {
        return self::_getPromptAttribute();
    }

    public static function getPromptInfo(?string $protocolVersion = null): V20241105Prompt|V20250326Prompts
    {

        return (self::_getPromptAttribute())->toPrompt($protocolVersion);
    }

    public static function getPromptListEntry(string $protocolVersion) : array
    {
        return (static::getPromptInfo($protocolVersion))->toValue();
    }
}
