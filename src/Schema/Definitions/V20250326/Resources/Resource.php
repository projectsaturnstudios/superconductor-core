<?php

namespace Superconductor\Schema\Definitions\V20250326\Resources;

use Superconductor\Schema\Definitions\V20241105\Resources\Resource as PreviousVersion;

class Resource extends PreviousVersion
{
    /**
     * @param string $name The name of the tool.
     * @param string|null $description A human-readable description of the tool.
     * @param object|null $inputSchema A JSON Schema object defining the expected parameters for the tool.
     *                                  Expected structure: { type: "object", properties?: { [key: string]: object }, required?: string[] }
     */
    public function __construct(
        string $uri,
        string $name,
        ?string $description = null,
        ?string $mimeType = null,
        public ?array $annotations = null,
        ?int $size = null, //in bytes
    ) {
        parent::__construct($uri, $name, $description, $mimeType, $size);
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
