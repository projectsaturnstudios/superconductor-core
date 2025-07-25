<?php

namespace MCP\Drivers;

use Exception;
use MCP\Sessions\ClientSession;

abstract class ClientConnectionDriver
{
    /**
     * @param ClientSession $session
     * @return never
     * @throws Exception
     */
    public function connect(ClientSession $session)
    {
        throw new \Exception('Connect method not implemented in ' . static::class);
    }

    public function list_tools(ClientSession $session, array $connection): array
    {
        throw new \Exception('list_tools method not implemented in ' . static::class);
    }
}
