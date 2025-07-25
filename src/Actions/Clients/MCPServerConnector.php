<?php

namespace MCP\Actions\Clients;

use Exception;
use MCP\Sessions\ClientSession;
use MCP\Drivers\ClientConnectionDriver;
use MCP\Support\Facades\MCPClientManager;
use Lorisleiva\Actions\Concerns\AsAction;

class MCPServerConnector
{
    use AsAction;

    /**
     * @param ClientSession $session
     * @return ClientSession
     * @throws Exception
     */
    public function handle(ClientSession $session): ClientSession
    {
        $client_driver = $session->server['driver'];

        /** @var ClientConnectionDriver $connection_driver */
        $connection_driver = MCPClientManager::driver($client_driver);

        return $connection_driver->connect($session);
    }
}
