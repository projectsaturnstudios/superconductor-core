<?php

namespace Superconductor\Mcp\Servers;

use stdClass;
use Superconductor\Capabilities\Prompts\Mcp\Capabilities\Prompts\Prompt;
use Superconductor\Capabilities\Resources\Mcp\Capabilities\Resources\Resource;
use Superconductor\Capabilities\Tools\Mcp\Capabilities\Tools\Tool;
use Superconductor\Capabilities\Tools\Support\Facades\MCP as Tools;
use Superconductor\Capabilities\Resources\Support\Facades\MCP as Resources;
use Superconductor\Capabilities\Prompts\Support\Facades\MCP as Prompts;

class MCPServer
{
    protected ?array $server_info = null;
    protected array $client_capabilities = [];
    protected array $server_capabilities = [];
    protected array $tools = [];
    protected array $resources = [];
    protected array $prompts = [];

    protected array $client_roots = [];
    protected array $client_sampling = [];
    protected bool $client_ready = false;


    public function __construct() {
        $this->server_info ??= config('superconductor.server_info', [
            'name' => 'Superconductor MCP Server',
            'version' => '20241105.5.0'
        ]);
    }

    public function getServerInfo(): array
    {
        return $this->server_info;
    }

    public function getServerCapabilities(): array
    {
        $results = $this->server_capabilities;

        return array_map(fn($capability) => empty($capability) ? new stdClass() : $capability, $results);
    }

    public function setClientCapabilities(array $capabilities): void
    {
        $this->client_capabilities = $capabilities;
    }

    public function setServerCapabilities(array $capabilities): void
    {
        $this->server_capabilities = $capabilities;
    }

    public function isClientReady(): bool
    {
        return $this->client_ready;
    }

    public function setClientReady(bool $ready = true): static
    {
        $this->client_ready = $ready;
        return $this;
    }

    public function getTools(): array
    {
        $tools = Tools::getTools();
        $these_tools = array_filter($this->tools, fn(string $tool) => isset($tools[$tool]));
        return empty($these_tools) ? [] : array_map(function(string $tool) use ($tools) {
            /** @var Tool $tool_class */
            $tool_class = $tools[$tool]->class;
            return $tool_class::getToolInfo();
        }, $these_tools);
    }

    public function getResources(): array
    {
        $resources = Resources::getResources();
        $these_resources = array_filter($this->resources, fn(string $resource) => isset($resources[$resource]));
        return empty($these_resources) ? [] : array_map(function(string $resource) use ($resources) {
            /** @var Resource $resource_class */
            $resource_class = $resources[$resource]->class;
            return $resource_class::getResourceInfo();
        }, $these_resources);
    }

    public function getPrompts(): array
    {
        $prompts = Prompts::getPrompts();
        $these_prompts = array_filter($this->prompts, fn(string $prompt) => isset($prompts[$prompt]));
        return empty($these_prompts) ? [] : array_map(function(string $prompt) use ($prompts) {
            /** @var Prompt $prompt_class */
            $prompt_class = $prompts[$prompt]->class;
            return $prompt_class::getPromptInfo();
        }, $these_prompts);
    }
}
