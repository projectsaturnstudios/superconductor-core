<?php

namespace Superconductor;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JSONRPC\Enums\RPCErrorCode;
use JSONRPC\Exceptions\RPCResponseException;
use JSONRPC\RPCErrorObject;
use Superconductor\Builders\MCPServerBuilder;
use Superconductor\Contracts\ModelContextProtocol;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Superconductor\Drivers\Transports\TransportProtocolDriver;
use Superconductor\Messages\ErrorResponseMessage;
use Superconductor\Messages\NotificationMessage;
use Superconductor\Messages\RequestMessage;
use Superconductor\Messages\ResponseMessage;
use Superconductor\StateMachines\Server\ActiveState;
use Superconductor\StateMachines\Server\ServerState;
use Superconductor\StateMachines\Server\UninitializedState;
use Superconductor\Support\Facades\Mcp;
use Superconductor\Support\Facades\McpSessions;
use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * Servers provide specialized context and capabilities
 *      Expose resources, tools and prompts via MCP primitives
 *      Operate independently with focused responsibilities
 *      Request sampling through client interfaces
 *      Must respect security constraints
 *      Can be local processes or remote services
 */
class MCPServer implements ModelContextProtocol
{
    protected ServerState $state;

    public function __construct(
        protected TransportProtocolDriver $transport,
        protected string $session_id,
        protected ?Authenticatable $user = null
    ) {
        /**
         * Initialize server state
         * 1. Server Info
         * 2. Tools
         * 3. Resources
         * 4. Prompts
         * 5. Experimental Features
         */
        $this->state = $this->setServerState();
    }

    private function setServerState(): ServerState
    {
        $results = new UninitializedState();
        return $results;
    }

    public function session_id(): ?string
    {
        return $this->session_id;
    }

    public function user(): ?Authenticatable
    {
        return $this->user;
    }

    public function start(): StreamedResponse|int
    {
        if($this->state instanceof UninitializedState) $this->state = new ActiveState();



        return $this->transport->start($this);
    }

    public static function boot(): void
    {
        app()->bind(static::class, function ($app, $params) {
            $builder = new MCPServerBuilder();
            if (isset($params['transport_protocol'])) {
                $builder->withTransport($params['transport_protocol']);
            }

            if (isset($params['session_id'])) {
                $builder->withSessionId($params['session_id']);
            }

            if (isset($params['user']) && $params['user'] instanceof Authenticatable) {
                $builder->withUser($params['user']);
            }

            return  $builder->make();
        });
    }

    public function receiveFromHttp(RequestMessage|NotificationMessage $message): JsonResponse|Response
    {
        if ($message instanceof NotificationMessage) {
            // Right now do nothing, but we will update this with a @todo
            /** @var StatefulMCPSession $session */
            $session = McpSessions::load($this->session_id)
                ->addNotification($message);

            return response()->noContent();
        }
        else
        {
            /** @var StatefulMCPSession $session */
            $session = McpSessions::load($this->session_id)
                ->addRequest($message);

            $message = $message->additional(['session_id' => $this->session_id, 'user' => $this->user?->id ?? null]);
            /** @var ResponseMessage $response */
            $response = Mcp::method($message);
            $session = $session->addResponse($response)->save();
            logger()->log('info', 'MCPServer received message', [
                'session_id' => $this->session_id,
                'message' => $message->toArray(),
                'response' => $response->toArray(),
                'session' => $session->toArray(),
            ]);
            return response()->noContent();
        }

        return response()->json($this->errorResponse(
            $message?->id ?? 0,
            -32600, "Invalid Request: " . $message->method
        )->toArray());
    }

    /**
     * @param string $method
     * @param string|int $id
     * @param int $error_code
     * @param string $msg
     * @param mixed|null $data
     * @return ErrorResponseMessage
     * @throws RPCResponseException
     */
    public function errorResponse(string|int $id, int $error_code, string $msg, mixed $data = null): ErrorResponseMessage
    {
        $code = RPCErrorCode::from($error_code);
        $error_object = new RPCErrorObject($code, $msg, $data);
        return new ErrorResponseMessage($id, $error_object);
    }

    public function send(mixed $message): void
    {
        $this->transport->send($message);
    }

}
