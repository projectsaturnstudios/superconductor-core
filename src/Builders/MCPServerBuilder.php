<?php

namespace Superconductor\Builders;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Superconductor\Drivers\Transports\TransportProtocolDriver;
use Superconductor\MCPServer;
use Superconductor\Support\Facades\Mcp;

class MCPServerBuilder
{
    protected ?string $session_id = null;
    protected ?TransportProtocolDriver $transport = null;
    protected ?Authenticatable $user = null;

    public function withTransport(string $transport_protocol): static
    {
        $this->transport = Mcp::protocol($transport_protocol);
        return $this;
    }

    public function withSessionId(string $session_id): static
    {
        $this->session_id = $session_id;
        return $this;
    }

    public function withUser(Authenticatable $user): static
    {
        $this->user = $user;
        return $this;
    }


    public function make(): MCPServer
    {
        $payload = [
            'transport' => $this->transport,
            'session_id' => $this->session_id,
        ];

        if ($this->user) {
            $payload['user'] = $this->user;
        }
        return new MCPServer(...$payload);
    }
}
