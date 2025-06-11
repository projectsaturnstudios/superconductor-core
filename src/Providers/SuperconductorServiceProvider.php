<?php

namespace Superconductor\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Superconductor\Console\Commands\MakeToolCommand;
use Superconductor\Managers\MCPCapabilityRegistrationManager;
use Superconductor\Managers\MCPMethodRegistrationManager;
use Superconductor\Managers\MCPSessionManager;
use Superconductor\Managers\MCPVersionManager;

class SuperconductorServiceProvider extends ServiceProvider
{
    protected array $config = [
        'mcp' => __DIR__ .'/../../config/mcp.php',
    ];

    protected array $commands = [
        MakeToolCommand::class
    ];

    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->publishConfigs();
        $this->bootManagers();
        $this->commands($this->commands);
    }

    protected function bootManagers(): void
    {
        MCPVersionManager::boot();
        MCPSessionManager::boot();
        MCPMethodRegistrationManager::boot();
        MCPCapabilityRegistrationManager::boot();
        //MCPLoopingManager::boot();
        //TransportProtocolManager::boot();
        //MCPServerInstance::boot();
        //StreamListenerManager::boot();
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
