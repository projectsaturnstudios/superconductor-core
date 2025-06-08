<?php

namespace Superconductor\Schema\Definitions\V20241105\Protocol;

use Superconductor\Schema\Definitions\V20241105\Base\Result;

class PingResult extends Result
{
    public function __construct(
        public string|int $id,
    )
    {
        parent::__construct();
    }

    public function toValue(): array
    {
        $results = parent::toValue();

        $results['id'] = $this->id;
        $results['result'] = new \stdClass();

        return $results;
    }
}
