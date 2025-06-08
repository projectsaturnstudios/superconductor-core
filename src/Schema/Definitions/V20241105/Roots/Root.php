<?php

namespace Superconductor\Schema\Definitions\V20241105\Roots;

use Spatie\LaravelData\Data;

class Root extends Data
{
    /**
     * @param string $name The name of the tool.
     * @param string|null $description A human-readable description of the tool.
     * @param object|null $inputSchema A JSON Schema object defining the expected parameters for the tool.
     *                                  Expected structure: { type: "object", properties?: { [key: string]: object }, required?: string[] }
     */
    public function __construct(
        public string $uri,
        public ?string $name = null,
    ) {
    }

    public function toValue(): array
    {
        $result = [
            'uri' => $this->uri,
        ];

        if ($this->name !== null) {
            $result['name'] = $this->name;
        }

        return $result;
    }
}
