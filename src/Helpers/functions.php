<?php

use Superconductor\Support\Facades\MCPCapabilities;
use Superconductor\Actions\Sessions\SessionGenerator;
use Superconductor\Schema\Definitions\V20241105\Tools\Tool;
use Superconductor\Schema\Definitions\V20241105\Resources\Resource;

if(!function_exists('mcp_auth_guard'))
{
    /**
     * Get the MCP auth guard.
     *
     * @return ?string
     */
    function mcp_auth_guard(): ?string
    {
        return config('mcp.endpoints.auth_guard', null);
    }
}

if(!function_exists('generate_mcp_session'))
{
    /**
     * Get the MCP auth guard.
     *
     * @return void
     */
    function generate_mcp_session(?string $session_id = null): void
    {
        (new SessionGenerator)->handle($session_id);
    }
}

if(!function_exists('is_looping'))
{
    /**
     * Get the MCP auth guard.
     *
     * @return bool
     */
    function is_looping(string $session_id): bool
    {
        $results = false;

        return $results;
    }
}

if(!function_exists('list_tools'))
{
    /**
     * Get list of available Tools
     *
     * @return Tool[]
     */
    function list_tools(?string $schema_version = null): array
    {
        $results = [];

        $tools = MCPCapabilities::list_capabilities('tools', $schema_version);
        foreach($tools as $tool)
        {
            $results[] = $tool;
        }

        return $results;
    }
}

if(!function_exists('list_resources'))
{
    /**
     * Get list of available resources
     *
     * @return Resource[]
     */
    function list_resources(?string $schema_version = null): array
    {
        $results = [];

        $resources = MCPCapabilities::list_capabilities('resources', $schema_version);
        foreach($resources as $resource)
        {
            $results[] = $resource;
        }

        return $results;
    }
}

if(!function_exists('list_prompts'))
{
    /**
     * Get the MCP auth guard.
     *
     * @return Tool[]
     */
    function list_prompts(?string $schema_version = null): array
    {
        $results = [];

        $prompts = MCPCapabilities::list_capabilities('prompts', $schema_version);
        foreach($prompts as $prompt)
        {
            $results[] = $prompt;
        }

        return $results;
    }
}
