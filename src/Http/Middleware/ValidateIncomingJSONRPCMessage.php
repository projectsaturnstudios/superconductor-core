<?php

namespace Superconductor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Support\Facades\SchemaManager;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;

class ValidateIncomingJSONRPCMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->isJson()) {
            /** @var JSONRPCError $error */
            $error = SchemaManager::error(0, MCPErrorCode::InvalidRequest, 'Invalid Request');
            return response()->json($error->toJsonRPC(), 400);
        }
        $results = $request->all();
        if(!array_key_exists('jsonrpc', $results))
        {
            /** @var JSONRPCError $error */
            $error = SchemaManager::error(0, MCPErrorCode::ParseError, 'Not JSONRPC 2.0');
            return response()->json($error->toJsonRPC(), 500);
        };

        logger()->log('info', "ValidateIncomingJSONRPCMessage - Results!", [
            'message' => $request->all(),
        ]);

        return $next($request);
    }
}
