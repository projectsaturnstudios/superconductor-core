<?php

namespace MCP;

use MCP\Capabilities\CapabilityExecution\CapabilityRequest;
use MCP\Capabilities\CapabilityExecution\CapabilityResult;
use MCP\Capabilities\CapabilityRegistrar;
use MCP\Capabilities\RequestRouting\CapabilityRegistrationService;

class ModelContextProtocol
{
    public function __construct(
        protected CapabilityRegistrar $capability_registrar
    ) {}

    public function capabilities(string $protocol): array
    {
        // Return the capabilities for the specified protocol
        return $this->capability_registrar->getCapabilities($protocol);
    }

    public function capability_routes(string $protocol, string $capability_group, ?string $subgroup = null): array
    {
        // Return the capabilities for the specified protocol
        return $this->capability_registrar->capability_routes($protocol, $capability_group, $subgroup);
    }


    //MCP::action('server','tools', 'echo', $request)
    //MCP::action('client','elicitation', 'ask-client-something', $request, true)
    public function action(string $protocol, string $capability, string $action, CapabilityRequest $request, bool $is_experimental = false): CapabilityResult
    {
        return $this->capability_registrar->dispatch($protocol, $capability, $action, $request, $is_experimental);

    }

    public function __call(string $method, mixed $args)
    {
        // Handle dynamic method calls
        try {
            /** @var CapabilityRegistrationService $registration_class */
            $registration_class = app("mcp-capability.{$method}");

            if($registration_class instanceof CapabilityRegistrationService) $registration_class::setCapability($this->capability_registrar, ...$args);
            else throw new \BadMethodCallException("Capability Registrar representing {$method} does not exist.");
        }
        catch(\Exception $e) {
            // Log the exception or handle it as needed
            dd($e);
        }
    }

    public static function boot(): void
    {
        app()->singleton('mcp', function ($app) {
            $capability_registrar = CapabilityRegistrar::make(config('mcp.capabilities'));
            $results = new static($capability_registrar);

            return $results;
        });
    }
}
