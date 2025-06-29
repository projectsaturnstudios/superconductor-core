<?php

namespace Superconductor\Providers;

use Illuminate\Support\ServiceProvider;
use JSONRPC\Support\Facades\RPCRouter;
use Superconductor\Drivers\Transports\TransportProtocolDriver;
use Superconductor\Managers\Sessions\StatefulSessionManager;
use Superconductor\Managers\Transports\TransportProtocolManager;
use Superconductor\Mcp\Controllers\Server\Tools\EchoTool;
use Superconductor\Mcp\Controllers\Server\Tools\ListCommandsTool;
use Superconductor\MCPServer;
use Superconductor\ModelContext;
use Superconductor\Routing\MCPServerNavigator;
use Superconductor\Rpc\Controllers\NotificationsController;
use Superconductor\Rpc\Controllers\SessionInitializationController;
use Superconductor\Rpc\Controllers\ToolsController;
use Superconductor\Support\Facades\McpRouter;

class SuperconductorServiceProvider extends ServiceProvider
{
    protected array $config = [
        'mcp' => __DIR__ .'/../../config/mcp.php',
    ];

    protected array $rpc_controllers = [
        ToolsController::class,
        NotificationsController::class,
        SessionInitializationController::class,
    ];

    protected array $test_tools = [
        EchoTool::class,
        ListCommandsTool::class,
    ];

    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->publishConfigs();
        $this->registerRpcControllers();
        $this->registerServices();
        $this->registerMcpControllers();
    }

    protected function registerServices(): void
    {
        ModelContext::boot();
        MCPServer::boot();
        MCPServerNavigator::boot();

        TransportProtocolManager::boot();
        StatefulSessionManager::boot();
    }

    protected function registerMcpControllers(): void
    {
        $test_tools = config('mcp.capabilities.server.tools.test_tools', false);
        if($test_tools) {
            foreach ($this->test_tools as $tool) {
                McpRouter::addCapability(new $tool, 'tools');
            }
        }

        // @todo - register other capabilities here
    }

    protected function registerRpcControllers(): void
    {
        foreach ($this->rpc_controllers as $controller) {
            RPCRouter::addMethod(new $controller);
        }
    }

    protected function publishConfigs() : void
    {
        $this->publishes([
            $this->config['mcp'] => config_path('mcp.php'),
        ], 'mcp');
    }

    protected function registerConfigs() : void
    {
        foreach ($this->config as $key => $path) {
            $this->mergeConfigFrom($path, $key);
        }
    }
}
