<?php

namespace Superconductor\Support\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ServerTool extends MCPServerCapability
{
    public function __construct(
        public readonly string $tool,
        public readonly string $description,
        public readonly array $inputSchema = [],

    ) {
        parent::__construct(route: $tool);
    }

    public function toTool() : array
    {
        return [
            'name' => $this->tool,
            'description' => $this->description,
            'inputSchema' => $this->inputSchema,
        ];
    }

    public function toValue() : array
    {
        $results = [
            'name' => $this->tool,
            'description' => $this->description,
        ];

        if (!empty($this->inputSchema)) {
            $results['inputSchema'] = $this->inputSchema;
        }

        return $results;
    }


}
