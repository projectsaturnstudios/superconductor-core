<?php

namespace Superconductor\Contracts\Versions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface MCPSchemaContract
{
    public function toJsonRPC() : array;
}
