<?php

namespace Superconductor\Schema\Definitions\V20250326\ResourceTemplates;

use Superconductor\Schema\Definitions\V20241105\ResourceTemplates\ResourceTemplate as PreviousVersion;

class ResourceTemplate extends PreviousVersion
{
    /**
     * @param string $name The name of the tool.
     * @param string|null $description A human-readable description of the tool.
     * @param object|null $inputSchema A JSON Schema object defining the expected parameters for the tool.
     *                                  Expected structure: { type: "object", properties?: { [key: string]: object }, required?: string[] }
     */
    public function __construct(
        string $uriTemplate,
        string $name,
        ?string $description = null,
        ?string $mimeType = null,
        public ?array $annotations = null,
    ) {
        parent::__construct($uriTemplate, $name, $description, $mimeType);
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
