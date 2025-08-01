<?php

namespace Superconductor\Schema\Definitions\V20241105\Tools;

use Spatie\LaravelData\Data;

class Tool extends Data
{
    /**
     * @param string $name The name of the tool.
     * @param string|null $description A human-readable description of the tool.
     * @param object|null $inputSchema A JSON Schema object defining the expected parameters for the tool.
     *                                  Expected structure: { type: "object", properties?: { [key: string]: object }, required?: string[] }
     */
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?array $inputSchema = null,
    ) {
    }

    public function toValue(): array
    {
        $result = [
            'name' => $this->name,
        ];

        if ($this->description !== null) {
            $result['description'] = $this->description;
        }

        if ($this->inputSchema !== null) {
            $result['inputSchema'] = $this->inputSchema;
        }

        return $result;
    }
}
