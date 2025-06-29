<?php

namespace Superconductor\Messages;

use JSONRPC\RPCErrorObject;

class ErrorResponseMessage extends ResponseMessage
{
    public function __construct(
        string|int $id,
        RPCErrorObject $error
    ) {
        parent::__construct(id:$id, error:$error);
    }
}
