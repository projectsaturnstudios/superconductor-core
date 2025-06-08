<?php

namespace Superconductor\Schema\Definitions\V20250326\Tools;

use Superconductor\Schema\Definitions\V20241105\Tools\Tool as PreviousVersion;

class Tool extends PreviousVersion
{
    public function __construct(
        string $name,
        ?string $description = null,
        ?array $inputSchema = null,
        public ?array $annotations = null,
    ) {
        parent::__construct($name, $description, $inputSchema);
    }

    public function toValue(): array
    {
        $result = parent::toValue();

        if ($this->annotations !== null) {
            $result['annotations'] = $this->annotations;
        }

        return $result;
    }

}
