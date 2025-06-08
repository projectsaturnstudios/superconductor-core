<?php

namespace Superconductor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Superconductor\Actions\Messages\MessageEvaluator;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\Definitions\V20250326\Base\JSONRPCRequest;
use Superconductor\Schema\MCPSchema;
use Superconductor\Support\Facades\MCPMethods;
use Superconductor\Support\Facades\SchemaManager;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCError;
use Superconductor\Support\Facades\SessionManager;

class DispatchMethod
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
        $session_id = $request->get('session_id');
        $session = SessionManager::createOrLoad($session_id);

        $msg_class = $request->get('message_class');
        $data = $request->get('message');
        $message = ($msg_class::from($data));

        $results = MCPMethods::dispatch($message, $session?->protocolVersion?? null);

        $payload = [
            'result_class' => $results::class,
            'result' => $results->toArray(),
        ];
        request()->merge($payload);

        return $next($request);
    }
}
