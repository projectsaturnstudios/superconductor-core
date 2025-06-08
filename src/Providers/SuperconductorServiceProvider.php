<?php

namespace Superconductor\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Superconductor\Managers\MCPCapabilityRegistrationManager;
use Superconductor\Managers\MCPMethodRegistrationManager;
use Superconductor\Managers\MCPSessionManager;
use Superconductor\Managers\MCPVersionManager;

class SuperconductorServiceProvider extends ServiceProvider
{
    protected array $config = [
        'mcp' => __DIR__ .'/../../config/mcp.php',
    ];

    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->publishConfigs();
        $this->bootManagers();

        Relation::enforceMorphMap([
            //'initialize'  => InitializeMethod::class,
        ]);
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
        $this->publishes($this->config, 'mcp');
    }

    protected function registerConfigs() : void
    {
        foreach ($this->config as $key => $path) {
            $this->mergeConfigFrom($path, $key);
        }
    }
}
