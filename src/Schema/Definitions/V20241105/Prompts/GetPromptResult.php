<?php

namespace Superconductor\Schema\Definitions\V20241105\Prompts;

use Superconductor\Schema\Definitions\V20241105\Base\Result;

class GetPromptResult extends Result
{
    public function __construct(
        public string|int $id,
        public array $messages = [],
        public ?string $description = null,
    ) {
        parent::__construct();
    }

    public function toValue(): array
    {
        $results = parent::toValue();

        $results['id'] = $this->id;
        $results['result'] = [
            'messages' => $this->messages,
        ];

        if($this->description){
            $results['result']['description'] = $this->description;
        }

        return $results;
    }
}
