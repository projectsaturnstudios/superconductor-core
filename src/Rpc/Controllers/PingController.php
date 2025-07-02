<?php

namespace MCP\Rpc\Controllers;

use JSONRPC\Rpc\Controllers\RpcController;
use Lorisleiva\Actions\Concerns\AsAction;

class PingController extends RpcController
{
    use AsAction;

    public function handle()
    {

    }
}
