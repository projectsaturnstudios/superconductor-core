<?php

namespace Superconductor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Superconductor\Actions\Messages\MessageEvaluator;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\MCPSchema;
use Superconductor\Support\Facades\SchemaManager;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Support\Facades\SessionManager;

class DetermineMessageType
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
        $results = $request->all();
        if(array_key_exists('session_id', $results)) unset($results['session_id']);

        $results = (new MessageEvaluator)->handle($results);
        if(!$results)
        {
            /** @var JSONRPCError $error */
            $error = SchemaManager::error(0, MCPErrorCode::InvalidRequest, 'Not a valid JSON RPC schema.');
            return response()->json($error->toJsonRPC(), 500);
        }

        $payload = [
            'message' => $results->toArray(),
            'message_class' => $results:: class
        ];
        request()->merge($payload);
        /** @var MCPSchema $results */
        logger()->log('info', "DetermineMessageType - Results!", [
            'class' => $results::class,
            'message' => $results,
        ]);

        return $next($request);
    }
}
