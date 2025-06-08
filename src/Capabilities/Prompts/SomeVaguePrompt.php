<?php

namespace Superconductor\Capabilities\Prompts;

use Superconductor\Schema\Definitions\V20241105\Prompts\GetPromptRequest as V20241105GetPromptRequest;
use Superconductor\Schema\Definitions\V20250326\Prompts\GetPromptRequest as V20250326GetPromptRequest;
use Superconductor\Support\Attributes\ActionablePrompt;

#[ActionablePrompt(
    name: "some-vague-prompt",
    description: "Test Prompt (but doesn't have to be!)",
    arguments: [
        [
            'name' => 'thing',
            'description' => "They style of talking the user wants, ie joke, story, speech, etc",
            "required" => true,
        ],
        [
            'name' => 'subject',
            'description' => "The subject the user wants to discuss. (optional)",
            "required" => false,
        ],
    ]
)]
class SomeVaguePrompt extends BasePrompt
{
    public function handle(V20241105GetPromptRequest|V20250326GetPromptRequest $incoming_message): array
    {
        $results = [
            'description' => "{$incoming_message->params['arguments']['thing']} prompt",
            'messages' => []
        ];
        if($content = $this->execute(...$incoming_message->params['arguments']))
        {
            $results['messages'] = $content;
        }

        return $results;
    }

    private function execute(string $thing, ?string $subject = null) : array
    {
        $convo = [];

        $convo[] = [
            'role' => 'user',
            'content' => [
                'type' => 'text',
                'text' => "Tell me a {$thing}".($subject ? " about {$subject}." : '.'),
            ]
        ];

        return $convo;
    }
}
