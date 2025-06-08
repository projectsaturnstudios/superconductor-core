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

class TransformToMCPSchema
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
        $msg_class = $request->get('message_class');
        $data = $request->get('message');
        $message = ($msg_class::from($data))->convertToMCP();
        $payload = [
            'native_type' => $msg_class::$type,
            'message' => $message->toArray(),
            'message_class' => $message::class,
        ];
        request()->merge($payload);

        return $next($request);
    }
}
