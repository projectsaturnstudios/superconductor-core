<?php

namespace Superconductor\Messages;

class SuccessfulResponseMessage extends ResponseMessage
{
    public function __construct(
        string|int $id,
        string|array $result
    ) {
        parent::__construct(id:$id, result:$result);
    }
}
