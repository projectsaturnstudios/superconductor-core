<?php

namespace Superconductor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Superconductor\Actions\Messages\MessageEvaluator;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\MCPSchema;
use Superconductor\Support\Facades\SchemaManager;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Support\Facades\SessionManager;

class AcceptIfNotProcessing
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
       $native_type = $request->get('native_type');
       if($native_type == 'result')
       {
           if(!config('mcp.processing.process_results', false)) return response()->noContent(202);
       }
        if($native_type == 'notification')
        {
            if(!config('mcp.processing.process_notifications', false)) return response()->noContent(202);
        }

        return $next($request);
    }
}
