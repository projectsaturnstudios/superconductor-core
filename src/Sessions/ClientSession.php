<?php

namespace MCP\Sessions;

use Exception;
use Spatie\LaravelData\Data;
use MCP\Support\Facades\MCPClientManager;

class ClientSession extends Data
{
    public array $server;
    public readonly ?string $session_id;
    public mixed $connection = null;
    public ?string $protocol = null;

    public array $messages_sent = [];
    public array $messages_received = [];

    public bool $has_tools = false;
    public bool $has_resources = false;
    public bool $has_prompts = false;

    public array $tools = [];
    public array $resources = [];
    public array $prompts = [];

    public mixed $tool_response = null;

    public function __construct($session_uuid = null)
    {
        if(is_null($session_uuid)) $this->session_id = new_uuid();
    }

    public function setServer(array $server): static
    {
        $this->server = $server;
        return $this;
    }

    public function setConnection(mixed $connection): static
    {
        $this->connection = $connection;
        return $this;
    }

    public function setHasTools(bool $has_tools): static
    {
        $this->has_tools = $has_tools;
        return $this;
    }

    public function setHasResources(bool $has_resources): static
    {
        $this->has_resources = $has_resources;
        return $this;
    }

    public function setHasPrompts(bool $has_prompts): static
    {
        $this->has_prompts = $has_prompts;
        return $this;
    }

    public function setProtocol(string $protocol): static
    {
        $this->protocol = $protocol;
        return $this;
    }

    public function logSendMessage(array $message): static
    {
        $this->messages_sent[] = $message;
        return $this;
    }

    public function logReceivedMessage(array $message): static
    {
        $this->messages_received[] = $message;
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function initListTools(): static
    {
        $pipes = $this->connection['pipes'] ?? null;
        $process = $this->connection['process'] ?? null;
        if(empty($pipes) || empty($process)) throw new \RuntimeException('Connection pipes or process are not set.');

        $shared = (MCPClientManager::driver($this->protocol))->list_tools($this, $this->connection);

        $this->messages_sent = $shared['session']->messages_sent;
        $this->messages_received = $shared['session']->messages_received;
        $this->tools = $shared['tools'] ?? [];

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function use_tool(string $name, ?array $args = null): static
    {
        $this->tool_response = null;
        $pipes = $this->connection['pipes'] ?? null;
        $process = $this->connection['process'] ?? null;
        if(empty($pipes) || empty($process)) throw new \RuntimeException('Connection pipes or process are not set.');

        $shared = (MCPClientManager::driver($this->protocol))->call_tool($this, $this->connection, [
            'name' => $name,
            'arguments' => !empty($args) ? $args : new \stdClass(),
        ]);

        $this->messages_sent = $shared['session']->messages_sent;
        $this->messages_received = $shared['session']->messages_received;
        $this->tool_response = $shared['response'] ?? null;

        return $this;
    }

    public function tool_response(): mixed
    {
        return $this->tool_response;
    }

}


