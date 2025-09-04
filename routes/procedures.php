<?php

use Superconductor\Rpc\Support\Facades\RPC;

RPC::method('ping', \Superconductor\Mcp\Rpc\Procedures\PingProcedure::class);
RPC::method('initialize', \Superconductor\Mcp\Rpc\Procedures\InitializeProcedure::class);
RPC::method('logging/setLevel', \Superconductor\Mcp\Rpc\Procedures\LoggingProcedure::class);
RPC::method('completion/complete', \Superconductor\Mcp\Rpc\Procedures\CompletionsProcedure::class);
RPC::method('notifications/progress', \Superconductor\Mcp\Rpc\Procedures\NotificationsProcedure::class.'@progress');
RPC::method('notifications/cancelled', \Superconductor\Mcp\Rpc\Procedures\NotificationsProcedure::class.'@cancelled');
RPC::method('notifications/initialized', \Superconductor\Mcp\Rpc\Procedures\NotificationsProcedure::class.'@initialized');
