<?php

namespace MCP\Mcp\Controllers;

use MCP\Capabilities\CapabilityExecution\CapabilityRequest;
use MCP\Capabilities\CapabilityExecution\CapabilityResult;

/**
 * @method void prep($request)
 * @method mixed exec()
 * @method CapabilityResult post(mixed $result)
 *
 */
abstract class McpController
{
    protected array $exec_params = [];

    public function handle(CapabilityRequest $request): CapabilityResult
    {
        $this->prep($request);

        $results = $this->exec(...$this->exec_params);
        return $this->post($results);
    }
}
