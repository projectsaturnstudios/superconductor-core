<?php

namespace Superconductor\Schema\Definitions\V20250326\Content;

use Spatie\LaravelData\Data;

class TextResourceContents extends Data
{
    /**
     * @param string $uri The URI of this resource.
     * @param string $text The text of the item. This must only be set if the item can actually be represented as text (not binary data).
     * @param string|null $mimeType The MIME type of this resource, if known.
     */
    public function __construct(
        public string $uri,
        public string $text,
        public ?string $mimeType = null,
    ) {
    }

    public function toValue(): array
    {
        $result = [
            'uri' => $this->uri,
        ];

        if ($this->mimeType !== null) {
            $result['mimeType'] = $this->mimeType;
        }

        $result['text'] = $this->text;

        return $result;
    }
}
