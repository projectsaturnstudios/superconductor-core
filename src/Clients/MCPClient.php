<?php

namespace Superconductor\Mcp\Clients;

use Exception;
use Superconductor\Capabilities\Prompts\DTO\Messages\Requests\GetPromptRequest;
use Superconductor\Capabilities\Prompts\DTO\Messages\Requests\ListPromptsRequest;
use Superconductor\Capabilities\Resources\DTO\Messages\Requests\ListResourcesRequest;
use Superconductor\Capabilities\Resources\DTO\Messages\Requests\ReadResourceRequest;
use Superconductor\Capabilities\Tools\DTO\Messages\Requests\CallToolRequest;
use Superconductor\Capabilities\Tools\DTO\Messages\Requests\ListToolsRequest;
use Superconductor\Mcp\DTO\Messages\Requests\InitializeRequest;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcNotification;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\DTO\Messages\RpcMessage;
use Superconductor\Transports\Stdio\DTO\Servers\ProcessCommandConfig;
use Superconductor\Transports\Stdio\StdioCommunicator;
use Superconductor\Transports\Stdio\Support\Facades\Stdio;
use Superconductor\Transports\StreamableHttp\DTO\Servers\StreamableServerConfig;
use Superconductor\Transports\StreamableHttp\StreamableCommunicator;
use Superconductor\Transports\StreamableHttp\Support\Facades\StreamableHttp;

class MCPClient
{
    protected ?string $session_id = null;
    protected int $msg_id = 0;

    protected ?array $client_info = null;
    protected array $client_capabilities = [];
    protected array $server_capabilities = [];
    protected array $roots = [];
    protected array $sampling = [];
    protected array $mcp_servers = [];

    protected array $server_tools = [];
    protected array $server_resources = [];
    protected array $server_prompts = [];
    protected bool $server_ready = false;
    protected array $server_info = [];

    protected null|StdioCommunicator|StreamableCommunicator $transport = null;

    public function __construct() {
        $this->client_capabilities ??= config('superconductor.client_info', [
            'name' => 'Superconductor MCP Client',
            'version' => '20241105.5.0'
        ]);
    }

    public function getClientInfo(): array
    {
        return $this->client_info;
    }

    public function getClientCapabilities(): array
    {
        $results = $this->client_capabilities;

        return array_map(fn($capability) => empty($capability) ? new \stdClass() : $capability, $results);
    }

    public function setServerCapabilities(array $capabilities): static
    {
        $this->server_capabilities = $capabilities;
        return $this;
    }

    public function getServerCapabilities(): array
    {
        return $this->server_capabilities;
    }

    public function setServerInfo(array $info): static
    {
        $this->server_info = $info;
        return $this;
    }

    public function setClientCapabilities(array $capabilities): void
    {
        $this->client_capabilities = $capabilities;
    }

    public function setSessionId(string $session_id): static
    {
        $this->session_id = $session_id;
        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->session_id;
    }

    public function isServerReady(): bool
    {
        return $this->server_ready;
    }

    public function setServerReady(bool $ready): void
    {
        $this->server_ready = $ready;
    }

    public function mcpServers(): array
    {
        return $this->mcp_servers;
    }

    public function setTransport(StdioCommunicator|StreamableCommunicator $transport): static
    {
        $this->transport = $transport;
        return $this;
    }

    public function incrementMessageId(): static
    {
        $this->msg_id++;
        return $this;
    }

    /**
     * @param string $mcp_server
     * @return $this
     * @throws Exception
     */
    public static function initialize(string $mcp_server): static
    {
        $reference = new static();
        if(!isset($reference->mcpServers()[$mcp_server])) throw new Exception("MCP Server '$mcp_server' not found");
        $transport_protocol = $reference->mcpServers()[$mcp_server];
        if(empty(config("mcp_servers.{$transport_protocol}.{$mcp_server}"))) throw new Exception("MCP Server configuration for '$mcp_server' with protocol '$transport_protocol' not found");
        $server_config = config("mcp_servers.{$transport_protocol}.{$mcp_server}");
        switch($transport_protocol)
        {
            case 'streamable':
                $server_config = new StreamableServerConfig(...$server_config);
                /** @var StreamableCommunicator $transport */
                $transport = StreamableHttp::client($server_config);
                $reference = $reference->setTransport($transport);
                $init_request = new InitializeRequest(
                    0,
                    protocolVersion: config('superconductor.protocol_versions.default'),
                    capabilities: $reference->getClientCapabilities(),
                    clientInfo: $reference->getClientInfo()
                );
                $response = $transport->send($init_request);

                if((isset($response['result'])))
                {
                    $response['result'] = RpcMessage::fromJsonRpc($response['result']);

                    if(($response['result'] instanceof RpcResult))
                    {
                        $results = $response['result']->result;
                        $reference = $reference->setServerCapabilities($results['capabilities'] ?? [])
                            ->setServerInfo($results['serverInfo'] ?? []);
                        if(isset($response['headers']['mcp-session-id'])) $reference = $reference->setSessionId($response['headers']['mcp-session-id'][0]);
                        $notification = new RpcNotification('notifications/initialized');
                        if(isset($response['headers']['mcp-session-id'])) $reply = $transport->send($notification, ['mcp-session-id' => $reference->getSessionId()]);
                        else $reply = $transport->send($notification);

                        return $reference->setTransport($transport)->incrementMessageId();
                    }
                    else throw new Exception("MCP Server '$mcp_server' not found");
                }
                else throw new Exception("MCP Server initialization failed: " . ($response->error['message'] ?? 'Unknown error'));

            case 'stdio':
                $command = new ProcessCommandConfig(...$server_config);
                $transport = Stdio::client($command);
                $reference = $reference->setTransport($transport);
                $init_request = new InitializeRequest(
                    0,
                    protocolVersion: config('superconductor.protocol_versions.default'),
                    capabilities: $reference->getClientCapabilities(),
                    clientInfo: $reference->getClientInfo()
                );
                $response = $transport->send('io', $init_request->toJsonRpc(true));
                if($response)
                {
                    $response = RpcMessage::fromJsonRpc($response);

                    if(($response instanceof RpcResult))
                    {
                        $results = $response->result;
                        $reference = $reference->setServerCapabilities($results['capabilities'] ?? [])
                            ->setServerInfo($results['serverInfo'] ?? []);
                        $notification = new RpcNotification('notifications/initialized');
                        $transport->send('write', $notification->toJsonRpc(true));

                        return $reference->setTransport($transport)->incrementMessageId();
                    }
                    else throw new Exception("MCP Server '$mcp_server' not found");
                }
                else throw new Exception("MCP Server '$mcp_server' not found");
                break;

            case 'sse':
            case 'streamable-webhooks':
            default:
                throw new Exception("MCP Server configuration for '$mcp_server over $transport_protocol' is not supported");
        }
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function loadTools(): static
    {
        $this->server_tools = collect($this->listTools()->result['tools'])->keyBy('name')->toArray();
        return $this->incrementMessageId();
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function loadResources(): static
    {
        $this->server_resources = collect($this->listResources()->result['resources'])->keyBy('uri')->toArray();
        return $this->incrementMessageId();
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function loadPrompts(): static
    {
        $this->server_prompts = collect($this->listPrompts()->result['prompts'])->keyBy('name')->toArray();
        return $this->incrementMessageId();
    }

    /**
     * @return RpcMessage
     * @throws Exception
     */
    public function listTools(): RpcMessage
    {
        if(!isset($this->server_capabilities['tools'])) throw new Exception("MCP Server does not support tools capability.");
        $response = $this->call(new ListToolsRequest($this->msg_id));

        if(isset($response['headers']) && isset($response['result']))
        {
            $response['result'] = RpcMessage::fromJsonRpc($response['result']);
            if($response['result'] instanceof RpcResult)
            {
                return $response['result'];
            }
            else throw new Exception("MCP Server returned invalid response to listTools request.");
        }
        else
        {
            $response = RpcMessage::fromJsonRpc($response);
            if($response instanceof RpcResult)
            {
                return $response;
            }
            else throw new Exception("MCP Server returned invalid response to listTools request.");

        }

    }

    /**
     * @return RpcMessage
     * @throws Exception
     */
    public function listResources(): RpcMessage
    {
        if(!isset($this->server_capabilities['resources'])) throw new Exception("MCP Server does not support resources capability.");
        $response = $this->call(new ListResourcesRequest($this->msg_id));
        if(isset($response['headers']) && isset($response['result']))
        {
            $response['result'] = RpcMessage::fromJsonRpc($response['result']);
            if($response['result'] instanceof RpcResult)
            {
                return $response['result'];
            }
            else throw new Exception("MCP Server returned invalid response to listResources request.");
        }
        else
        {
            $response = RpcMessage::fromJsonRpc($response);
            if($response instanceof RpcResult)
            {
                return $response;
            }
            else throw new Exception("MCP Server returned invalid response to listTools request.");

        }
    }

    /**
     * @return RpcMessage
     * @throws Exception
     */
    public function listPrompts(): RpcMessage
    {
        if(!isset($this->server_capabilities['prompts'])) throw new Exception("MCP Server does not support prompts capability.");
        $response = $this->call(new ListPromptsRequest($this->msg_id));
        if(isset($response['headers']) && isset($response['result']))
        {
            $response['result'] = RpcMessage::fromJsonRpc($response['result']);
            if($response['result'] instanceof RpcResult)
            {
                return $response['result'];
            }
            else throw new Exception("MCP Server returned invalid response to listPrompts request.");
        }
        else
        {
            $response = RpcMessage::fromJsonRpc($response);
            if($response instanceof RpcResult)
            {
                return $response;
            }
            else throw new Exception("MCP Server returned invalid response to listTools request.");

        }
    }

    public function callTool(string $tool_name, array $arguments = []): RpcResult|RpcError
    {
        if(!isset($this->server_tools[$tool_name])) throw new Exception("MCP Server does not support this tool: $tool_name");
        $response = $this->call(new CallToolRequest($this->msg_id, $tool_name, $arguments));
        if(isset($response['headers']) && isset($response['result']))
        {
            $response['result'] = RpcMessage::fromJsonRpc($response['result']);
            if($response['result'] instanceof RpcResult)
            {
                $this->incrementMessageId();
                return $response['result'];
            }
            else throw new Exception("MCP Server returned invalid response to listPrompts request.");
        }
        else
        {
            $response = RpcMessage::fromJsonRpc($response);
            if($response instanceof RpcResult)
            {
                return $response;
            }
            else throw new Exception("MCP Server returned invalid response to listTools request.");

        }
    }

    public function readResource(string $uri): RpcResult|RpcError
    {
        if(!isset($this->server_resources[$uri])) throw new Exception("MCP Server does not support this resource: $uri");
        $response = $this->call(new ReadResourceRequest($this->msg_id, $uri));
        if(isset($response['headers']) && isset($response['result']))
        {
            $response['result'] = RpcMessage::fromJsonRpc($response['result']);
            if($response['result'] instanceof RpcResult)
            {
                $this->incrementMessageId();
                return $response['result'];
            }
            else throw new Exception("MCP Server returned invalid response to readResource request.");
        }
        else
        {
            $response = RpcMessage::fromJsonRpc($response);
            if($response instanceof RpcResult)
            {
                return $response;
            }
            else throw new Exception("MCP Server returned invalid response to listTools request.");

        }
    }

    public function getPrompt(string $prompt_name, array $arguments = []): RpcResult|RpcError
    {
        if(!isset($this->server_prompts[$prompt_name])) throw new Exception("MCP Server does not support this prompt: $prompt_name");
        $response = $this->call(new GetPromptRequest($this->msg_id, $prompt_name, $arguments));
        if(isset($response['headers']) && isset($response['result']))
        {
            $response['result'] = RpcMessage::fromJsonRpc($response['result']);
            if($response['result'] instanceof RpcResult)
            {
                $this->incrementMessageId();
                return $response['result'];
            }
            else throw new Exception("MCP Server returned invalid response to getPrompt request.");
        }
        else
        {
            $response = RpcMessage::fromJsonRpc($response);
            if($response instanceof RpcResult)
            {
                return $response;
            }
            else throw new Exception("MCP Server returned invalid response to listTools request.");

        }

    }

    public function tools(): array
    {
        return $this->server_tools;
    }
    public function resources(): array
    {
        return $this->server_resources;
    }
    public function prompts(): array
    {
        return $this->server_prompts;
    }


    /**
     * @param RpcRequest $request
     * @return array|bool
     * @throws Exception
     */
    protected function call(RpcRequest $request): array|bool
    {
        if(empty($this->transport)) throw new Exception("MCP Client transport not initialized.");
        if($this->transport instanceof StreamableCommunicator)
        {
            $headers = [];
            if($this->getSessionId()) $headers['mcp-session-id'] = $this->getSessionId();
            return $this->transport->send($request, $headers);
        }
        elseif($this->transport instanceof StdioCommunicator)
        {
            return $this->transport->send('io', $request->toJsonRpc(true));
        }
        else throw new Exception("MCP Client transport not initialized.");
    }

    public function notify(RpcNotification $notification): bool
    {
        if(empty($this->transport)) throw new Exception("MCP Client transport not initialized.");
        if($this->transport instanceof StreamableCommunicator)
        {

        }
        elseif($this->transport instanceof StdioCommunicator)
        {

        }
        else
        {

        }
    }
}
