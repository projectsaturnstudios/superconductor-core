<?php

namespace Superconductor\Schema\Definitions\V20241105\Content;

use Spatie\LaravelData\Data;

class TextContent extends Data
{
    /**
     * @param string $text The text content of the message.
     * @param array|null $annotations Optional annotations for the client.
     *                                Expected structure: { audience?: Role[], priority?: number }
     */
    public function __construct(
        public string $text,
        public ?array $annotations = null,
    ) {
    }

    public function toValue(): array
    {
        $result = [
            'type' => 'text',
            'text' => $this->text,
        ];

        if ($this->annotations !== null) {
            $result['annotations'] = $this->annotations;
        }

        return $result;
    }
} 