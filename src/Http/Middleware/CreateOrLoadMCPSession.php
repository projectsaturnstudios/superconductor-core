<?php

namespace Superconductor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Support\Facades\SchemaManager;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Support\Facades\SessionManager;

class CreateOrLoadMCPSession
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
        try {
            generate_mcp_session(request()->header('mcp-session-id'));
        }
        catch(\DomainException $e)
        {
            /** @var JSONRPCError $error */
            $error = SchemaManager::error(0, MCPErrorCode::InternalError, 'Session Not Found');
            return response()->json($error->toJsonRPC(), 404);
        }

        return $next($request);
    }
}
