<?php

namespace MCP\Providers;

use Illuminate\Support\ServiceProvider;
use MCP\Console\Commands\MCPInspector\RunMCPInspectorCommand;
use MCP\Managers\RemoteServerManager;
use MCP\ModelContextProtocol;


class SuperconductorServiceProvider extends ServiceProvider
{
    protected array $config = [
        'mcp' => __DIR__ .'/../../config/mcp.php',
        'mcp-servers' => __DIR__ .'/../../config/mcp-servers.php',
    ];

    protected array $commands = [
        RunMCPInspectorCommand::class,
    ];

    protected array $rpc_controllers = [

    ];

    protected array $test_tools = [

    ];

    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->publishConfigs();
        ModelContextProtocol::boot();
        $this->registerRPCMethods();
        RemoteServerManager::boot();
        $this->registerCommands();

    }

    protected function registerRPCMethods():  void
    {

        $this->loadRoutesFrom(__DIR__.'/../../routes/rpc.php');

        // Check if there is a tools.php route in the main source's routes folder
        /*$mainToolsRoutePath = base_path('routes/rpc.php');
        if (file_exists($mainToolsRoutePath)) {
            $this->loadRoutesFrom($mainToolsRoutePath);
        }*/
    }

    protected function registerCommands(): void
    {
        $this->commands($this->commands);
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
