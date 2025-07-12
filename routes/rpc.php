<?php

use JSONRPC\Support\Facades\Rpc;

Rpc::method('ping', \MCP\Rpc\Controllers\PingController::class);
Rpc::method('initialize', \MCP\Rpc\Controllers\InitializeController::class);
Rpc::method('notifications', \MCP\Rpc\Controllers\NotificationsController::class);
