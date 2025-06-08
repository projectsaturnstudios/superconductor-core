<?php

namespace Superconductor\Sessions;

use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Superconductor\Support\Facades\SessionManager;

class AbstractMCPSession extends Data
{
    public ?string $user_id = null;
    public readonly string $session_id;
    public ?string $protocolVersion = null;

    public function __construct(?string $id = null)
    {
        $this->session_id = $id ?? Str::uuid()->toString();
    }

    public function withProtocolVersion(string $version): static
    {
        $this->protocolVersion = $version;
        return $this;
    }

    public function withUser(string $user_id): static
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function save(): static
    {
        return SessionManager::save($this);
    }

    public static function load(string $session_id): ?static
    {
        return SessionManager::load($session_id);
    }
}
