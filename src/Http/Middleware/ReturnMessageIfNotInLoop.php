<?php

namespace Superconductor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Superconductor\Actions\Messages\MessageEvaluator;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\Definitions\V20241105\Base\Result;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\MCPSchema;
use Superconductor\Support\Facades\SchemaManager;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Support\Facades\SessionManager;

class ReturnMessageIfNotInLoop
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
        if(is_looping($request->get('session_id'))) return $next($request);

        $msg_class = $request->get('result_class');
        $data = $request->get('result');
        /** @var Result $message */
        $message = ($msg_class::from($data));

        //dd($message::class, $message->toJsonRPC());
        logger()->log('info', 'ReturnMessageIfNotInLoop', [
            'result_class' => $message
        ]);

        return response()->json($message->toJsonRPC())->withHeaders([
            'mcp-session-id' => $request->get('session_id')
        ]);
    }
}
