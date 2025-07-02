<?php

namespace MCP\Capabilities;

use MCP\Capabilities\CapabilityExecution\CapabilityRequest;
use MCP\Capabilities\CapabilityExecution\CapabilityResult;
use MCP\Mcp\Controllers\McpController;

class CapabilityRegistrar
{
    public function __construct(
        protected array $server_capabilities = [],
        protected array $client_capabilities = []
    ) {

    }

    public function getCapabilities(string $protocol): array
    {
        if($protocol == 'server') {
            return $this->getServerCapabilities();
        } elseif($protocol == 'client') {
            return $this->getClientCapabilities();
        }

        throw new \InvalidArgumentException("getCapabilities - Unknown protocol: {$protocol}");
    }

    public function getServerCapabilities(): array
    {
        $results = [];

        foreach($this->server_capabilities as $capability => $routes) {
            if($capability != 'experimental') $results[$capability] = true;
            else {
                $results['experimental'] = [];
                foreach($routes as $experimental_capability => $exp_config) {
                    $results['experimental'][$experimental_capability] = true;
                }
            };
        }

        return $results;
    }

    public function getClientCapabilities(): array
    {
        $results = [];

        foreach($this->client_capabilities as $capability => $routes) {
            if($capability != 'experimental') $results[$capability] = true;
            else {
                $results['experimental'] = [];
                foreach($routes as $experimental_capability => $exp_config) {
                    $results['experimental'][$experimental_capability] = true;
                }
            };
        }

        return $results;
    }

    public function setCapability(string $protocol, string $capability_group, string $cap_name, string $cap_class, bool $is_experimental = false): void
    {
        if($protocol == 'server') $this->setServerCapability($capability_group, $cap_name, $is_experimental);
        elseif($protocol == 'client') $this->setClientCapability($capability_group, $cap_name, $cap_class, $is_experimental);
        else throw new \InvalidArgumentException("setCapability - Unknown protocol: {$protocol}");
    }

    public function setClientCapability(string $capability_group, string $cap_name, string $cap_class, bool $is_experimental = false): void
    {
        if($is_experimental) $this->client_capabilities['experimental'][$capability_group][$cap_name] = $cap_class;
        else $this->client_capabilities[$capability_group][$cap_name] = $cap_class;
    }

    public function setServerCapability(string $capability_group, string $cap_name, string $cap_class, bool $is_experimental = false): void
    {
        if($is_experimental) $this->server_capabilities['experimental'][$capability_group][$cap_name] = $cap_class;
        else $this->server_capabilities[$capability_group][$cap_name] = $cap_class;
    }

    public function capability_routes(string $protocol, string $capability_group, ?string $subgroup = null): array
    {
        if($protocol == 'server') {
            $group = $this->server_capabilities[$capability_group];
            if($subgroup) $group = $group[$subgroup];
            return $group;
        }
        elseif($protocol == 'client') {
            $group = $this->client_capabilities[$capability_group];
            if($subgroup) $group = $group[$subgroup];
            return $group;
        }

        throw new \InvalidArgumentException("capability_routes - Unknown protocol: {$protocol}");
    }

    private function executeAction(array $actions, string $action, CapabilityRequest $request): CapabilityResult
    {
        /** @var McpController $action_class */
        $action_class = $actions[$action] ?? null;
        if($action_class) return (new $action_class)->handle($request);

        throw new \InvalidArgumentException("executeAction - Unknown action: {$action}");
    }

    public function dispatch(string $protocol, string $capability, string $action, CapabilityRequest $request, bool $is_experimental = false): CapabilityResult
    {

        if($protocol == 'server') return $is_experimental ? $this->executeAction($this->server_capabilities['experimental'][$capability], $action, $request, $is_experimental) : $this->executeAction($this->server_capabilities[$capability], $action, $request, $is_experimental);
        elseif($protocol == 'client') return  $is_experimental ? $this->executeAction($this->client_capabilities['experimental'][$capability], $action, $request, $is_experimental) : $this->executeAction($this->client_capabilities[$capability], $action, $request, $is_experimental);

        throw new \InvalidArgumentException("dispatch - Unknown protocol: {$protocol}");
    }

    public static function make(array $capabilities_config): static
    {
        $server_capabilities = [];
        $client_capabilities = [];

        foreach($capabilities_config as $protocol => $capabilities)
        {
            if($protocol == 'client')
            {
                foreach($capabilities as $capability => $config)
                {

                    if($config['enabled']) $client_capabilities[$capability] = [];
                    if(($capability == 'experimental') && $config['enabled'])
                    {
                        $client_capabilities[$capability] = [];
                        foreach($config['capabilities'] as $experimental_capability => $exp_config)
                        {
                            if($exp_config['enabled']) $client_capabilities[$capability][$experimental_capability] = [];
                        }
                        if(count($client_capabilities[$capability]) == 0) unset($client_capabilities[$capability]);
                    }
                }
            }
            elseif($protocol == 'server')
            {
                foreach($capabilities as $capability => $config)
                {
                    if($config['enabled']) $server_capabilities[$capability] = [];
                    if(($capability == 'experimental') && ($config['enabled']))
                    {
                        $server_capabilities[$capability] = [];
                        foreach($config['capabilities'] as $experimental_capability => $exp_config)
                        {
                            if($exp_config['enabled']) $server_capabilities[$capability][$experimental_capability] = [];
                        }
                        if(count($server_capabilities[$capability]) == 0) unset($server_capabilities[$capability]);
                    }


                }
            }
            else throw new \InvalidArgumentException("make Unknown protocol: {$protocol}");
        }

        return new static($server_capabilities, $client_capabilities);
    }

}

