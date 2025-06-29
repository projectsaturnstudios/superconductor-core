<?php

namespace Superconductor;


use Spatie\LaravelData\Data;
use Superconductor\Messages\NotificationMessage;
use Superconductor\Messages\RequestMessage;
use Superconductor\Messages\ResponseMessage;
use Superconductor\Support\Facades\McpSessions;

class StatefulMCPSession  extends Data
{
    public array $requests = [];
    public array $notifications = [];
    public array $responses = [];

    public array $client_capabilities = [];

    public function __construct(
        public readonly string|int $session_id,
    ) {}

    public function save(): static
    {
        return MCPSessions::save($this);
    }

    public function addRequest(RequestMessage $message): static
    {
        $this->requests[$message->id] = [
            'message' => $message,
            'status' => 'pending',
        ];
        return $this->save();
    }

    public function addResponse(ResponseMessage $message): static
    {
        $this->responses[$message->id] = [
            'message' => $message,
            'status' => 'pending',
        ];

        $this->requests[$message->id]['status'] = 'completed';
        return $this->save();
    }

    public function addNotification(NotificationMessage $message): static
    {
        $this->notifications[] = [
            'message' => $message,
            'status' => 'pending',
        ];

        return $this->save();
    }

    public function completeResponse(ResponseMessage $message): static
    {
        $this->responses[$message->id]['status'] = 'completed';
        return $this->save();
    }

    public function addClientCapability(string $name, array $capability): static
    {
        if (!in_array($name, $this->client_capabilities)) {
            $this->client_capabilities[$name] = $capability;
        }
        return $this->save();
    }

    public function getMessagesToSend(): array
    {
        $messages = [];
        foreach ($this->responses as $request) {
            if ($request['status'] == 'pending') {
                $messages[] = $request['message'];
            }
        }
        return $messages;
    }

}
