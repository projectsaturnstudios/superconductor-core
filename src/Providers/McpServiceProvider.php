<?php

namespace Superconductor\Mcp\Providers;

use ProjectSaturnStudios\LaravelDesignPatterns\Providers\BaseServiceProvider;
use Superconductor\Mcp\Clients\ClientRegistrar;
use Superconductor\Mcp\Servers\ServerRegistrar;
use Superconductor\Rpc\ProcedureRegistrar;

class McpServiceProvider extends BaseServiceProvider
{
    protected array $config = [
        'superconductor' => __DIR__ . '/../../config/superconductor.php',
        'mcp_servers'    => __DIR__ . '/../../config/mcp-servers.php',
    ];

    protected array $publishable_config = [
        ['key' => 'superconductor', 'file_path' => __DIR__ . '/../../config/superconductor.php', 'groups' => ['superconductor']],
        ['key' => 'mcp_servers',    'file_path' => __DIR__ . '/../../config/mcp-servers.php', 'groups' => ['mcp-servers']],
    ];

    protected array $routes = [
        __DIR__ . '/../../routes/procedures.php',
    ];

    protected array $commands = [
        //ListProceduresCommand::class,
    ];
    protected array $bootables = [

    ];

    public function register(): void
    {
        $this->registerConfigs();
        ServerRegistrar::boot();
        ClientRegistrar::boot();
    }
}
