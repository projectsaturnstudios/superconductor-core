<?php

namespace Superconductor\Schema\Definitions\V20250326\Protocol;

use Superconductor\Schema\Definitions\V20250326\Base\Result;

class InitializeResult extends Result
{
    /**
     * @param string $protocolVersion The version of the Model Context Protocol that the server wants to use
     * @param ServerCapabilities $capabilities Capabilities the server supports
     * @param Implementation $serverInfo Information about the server implementation
     * @param string|null $instructions Instructions describing how to use the server and its features
     * @param array|null $_meta Additional metadata for the response
     */
    public function __construct(
        public string|int $id,
        public string $protocolVersion,
        public array $capabilities,
        public array $serverInfo,
        public ?string $instructions = null,
        ?array $_meta = null,
    ) {
        parent::__construct($_meta);
    }

    public function toValue(): array
    {
        $results = parent::toValue();

        $results['id'] = $this->id;
        $results['result'] = [];
        $results['result']['protocolVersion'] = $this->protocolVersion;
        $results['result']['capabilities'] = $this->capabilities;//->toValue();
        $results['result']['serverInfo'] = $this->serverInfo;//->toValue();

        if ($this->instructions !== null) {
            $results['result']['instructions'] = $this->instructions;
        }

        return $results;
    }
}
