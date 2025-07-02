<?php

namespace MCP\Providers;

use Illuminate\Support\ServiceProvider;
use MCP\ModelContextProtocol;


class SuperconductorServiceProvider extends ServiceProvider
{
    protected array $config = [
        'mcp' => __DIR__ .'/../../config/mcp.php',
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

    }

    protected function registerServices(): void
    {

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
