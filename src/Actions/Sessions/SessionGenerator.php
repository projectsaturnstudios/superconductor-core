<?php

namespace Superconductor\Actions\Sessions;

use Lorisleiva\Actions\Concerns\AsAction;
use Superconductor\Sessions\AbstractMCPSession;
use Superconductor\Support\Facades\SessionManager;

class SessionGenerator
{
    use AsAction;

    public function handle(?string $session_id = null): void
    {
        /** @var AbstractMCPSession $session */
        $session = SessionManager::createOrLoad($session_id);
        if($user = auth(mcp_auth_guard())->user())
        {
            $session = $session->withUser($user->getKey());
            request()->merge(['session_id' => $session->session_id]);
        }

        if(array_key_exists('params', $req = request()->all()))
        {
            if(array_key_exists('protocolVersion', $req['params']))
            {
                $session = $session->withProtocolVersion($req['params']['protocolVersion']);
            }
        }

        $session->save();
    }
}
