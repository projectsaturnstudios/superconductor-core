<?php

namespace Superconductor\Schema\Definitions\V20241105\Resources;

use Spatie\LaravelData\Data;

class Resource extends Data
{
    /**
     * @param string $name The name of the tool.
     * @param string|null $description A human-readable description of the tool.
     * @param object|null $inputSchema A JSON Schema object defining the expected parameters for the tool.
     *                                  Expected structure: { type: "object", properties?: { [key: string]: object }, required?: string[] }
     */
    public function __construct(
        public string $uri,
        public string $name,
        public ?string $description = null,
        public ?string $mimeType = null,
        public ?int $size = null, //in bytes
    ) {
    }

    public function toValue(): array
    {
        $result = [
            'uri' => $this->uri,
            'name' => $this->name,
        ];

        if ($this->description !== null) {
            $result['description'] = $this->description;
        }

        if ($this->mimeType !== null) {
            $result['mimeType'] = $this->mimeType;
        }

        if ($this->size !== null) {
            $result['size'] = $this->size;
        }

        return $result;
    }
}
