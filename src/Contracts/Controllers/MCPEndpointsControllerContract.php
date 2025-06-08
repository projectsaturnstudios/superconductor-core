<?php

namespace Superconductor\Contracts\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface MCPEndpointsControllerContract
{
    public function message(): Response;
    public function stream():  Response;
}
