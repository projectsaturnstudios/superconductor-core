<?php

namespace Superconductor\Schema\Definitions\V20241105\Maps;

use Superconductor\Schema\Definitions\V20241105\Base\Request;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20241105\Prompts\GetPromptRequest;
use Superconductor\Schema\Definitions\V20241105\Prompts\ListPromptsRequest;
use Superconductor\Schema\Definitions\V20241105\Protocol\PingRequest;
use Superconductor\Schema\Definitions\V20241105\Resources\ListResourcesRequest;
use Superconductor\Schema\Definitions\V20241105\Resources\ReadResourceRequest;
use Superconductor\Schema\Definitions\V20241105\ResourceTemplates\ListResourceTemplatesRequest;
use Superconductor\Schema\Definitions\V20241105\Tools\CallToolRequest;
use Superconductor\Schema\Definitions\V20241105\Tools\ListToolsRequest;
use Superconductor\Schema\Definitions\V20241105\Protocol\InitializeRequest;

class RequestUnion
{
    protected static array $schema = [
        'initialize' => InitializeRequest::class,
        'ping' => PingRequest::class,
        'tools/list' => ListToolsRequest::class,
        'tools/call' => CallToolRequest::class,
        'resources/list' => ListResourcesRequest::class,
        'resources/read' => ReadResourceRequest::class,
        'resources/templates/list' => ListResourceTemplatesRequest::class,
        'prompts/list' => ListPromptsRequest::class,
        'prompts/get' => GetPromptRequest::class,

    ];

    public static function create(JSONRPCRequest $request): Request
    {
        $class = self::$schema[$request->method];
        return $class::convert($request);
    }
}
