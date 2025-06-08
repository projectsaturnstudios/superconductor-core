<?php

namespace Superconductor\Schema\Definitions\V20250326\Maps;

use Superconductor\Schema\Definitions\V20250326\Prompts\GetPromptRequest;
use Superconductor\Schema\Definitions\V20250326\Resources\ReadResourceRequest;
use Superconductor\Schema\Definitions\V20250326\Prompts\ListPromptsRequest;
use Superconductor\Schema\Definitions\V20250326\Resources\ListResourcesRequest;
use Superconductor\Schema\Definitions\V20250326\ResourceTemplates\ListResourceTemplatesRequest;
use Superconductor\Schema\Definitions\V20250326\Base\Request;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\Definitions\V20250326\Protocol\PingRequest;
use Superconductor\Schema\Definitions\V20250326\Tools\CallToolRequest;
use Superconductor\Schema\Definitions\V20250326\Tools\ListToolsRequest;
use Superconductor\Schema\Definitions\V20250326\Protocol\InitializeRequest;

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
