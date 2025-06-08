<?php

namespace Superconductor\Actions\Messages;

use Lorisleiva\Actions\Concerns\AsAction;
use Superconductor\Enums\MCPErrorCode;
use Superconductor\Schema\MCPSchema;
use Superconductor\Sessions\AbstractMCPSession;
use Superconductor\Support\Facades\SchemaManager;
use Superconductor\Support\Facades\SessionManager;

class MessageEvaluator
{
    use AsAction;

    public function handle(array $message = null): MCPSchema|false
    {
        $results = false;

        if(array_key_exists('error', $message))
        {
            /** @var MCPSchema $results */
            $results = SchemaManager::error(
                id: $message['id'],
                code: MCPErrorCode::from($message['error']['code']),
                message: $message['error']['message'],
                data: $message['error']['data'] ?? []
            );
        }
        elseif(array_key_exists('result', $message))
        {
            /** @var MCPSchema $results */
            $results = SchemaManager::result(
                id: $message['id'],
                result: $message['result'],
            );
        }
        elseif(array_key_exists('id', $message))
        {
            /** @var MCPSchema $results */
            $results = SchemaManager::request(
                id: $message['id'],
                method: $message['method'],
                params: $message['params'] ?? [],
            );
        }
        elseif(array_key_exists('method', $message))
        {
            /** @var MCPSchema $results */
            $results = SchemaManager::notification(
                method: $message['method'],
                params: $message['params'] ?? [],
            );
        }

        return $results;
    }
}
