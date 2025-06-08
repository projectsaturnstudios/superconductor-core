<?php

namespace Superconductor\Schema\Definitions\V20241105\ResourceTemplates;

use Spatie\LaravelData\Data;

class ResourceTemplate extends Data
{
    /**
     * @param string $name The name of the tool.
     * @param string|null $description A human-readable description of the tool.
     * @param object|null $inputSchema A JSON Schema object defining the expected parameters for the tool.
     *                                  Expected structure: { type: "object", properties?: { [key: string]: object }, required?: string[] }
     */
    public function __construct(
        public string $uriTemplate,
        public string $name,
        public ?string $description = null,
        public ?string $mimeType = null,
    ) {
    }

    public function toValue(): array
    {
        $result = [
            'uriTemplate' => $this->uriTemplate,
            'name' => $this->name,
        ];

        if ($this->description !== null) {
            $result['description'] = $this->description;
        }

        if ($this->mimeType !== null) {
            $result['mimeType'] = $this->mimeType;
        }


        return $result;
    }
}
