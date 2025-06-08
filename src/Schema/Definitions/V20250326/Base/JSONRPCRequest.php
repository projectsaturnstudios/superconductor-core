<?php

namespace Superconductor\Schema\Definitions\V20250326\Base;

use Illuminate\Support\Facades\Log;
use Superconductor\Schema\Definitions\V20250326\Maps\RequestUnion;
use Superconductor\Schema\Definitions\V20241105\Base\JSONRPCRequest as PreviousVersion;

class JSONRPCRequest extends PreviousVersion
{
    public function convertToMCP(): Request
    {
        return RequestUnion::create($this);
    }
}
