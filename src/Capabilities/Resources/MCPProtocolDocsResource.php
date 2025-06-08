<?php

namespace Superconductor\Capabilities\Resources;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Superconductor\Schema\Definitions\V20241105\Resources\ReadResourceRequest as V20241105ReadResourceRequest;
use Superconductor\Schema\Definitions\V20250326\Resources\ReadResourceRequest as V20250326ReadResourceRequest;

use Superconductor\Support\Attributes\ReadableResource;
use Superconductor\Schema\Definitions\V20241105\Base\Result;

#[ReadableResource(
    uri: 'mcp://protocol-docs.txt',
    name: 'MCP Protocol Documentation',
    description: 'Documentation for the MCP protocol, including version history and usage guidelines.',
    mimeType: 'text/plain',
)]
class MCPProtocolDocsResource extends BaseResource
{
    public function handle(V20241105ReadResourceRequest|V20250326ReadResourceRequest $incoming_message): array
    {
        $results = [
            'contents' => [],
        ];

        if($content = $this->execute())
        {
            $uri = $this->getResourceAttribute()->uri;
            $text_content_class = $incoming_message::getTextResourceContextObj();
            $text_content = new $text_content_class($uri, $content, 'text/plain');
            $results['contents'][] = $text_content->toValue();
        }

        return $results;
    }

    private function execute() : string
    {
        return Cache::get('mcp://protocol-docs.txt', function() {
            $response = Http::get('https://modelcontextprotocol.io/llms-full.txt');

            if($response->successful()) {
                $content = $response->body();
                Cache::put('mcp://protocol-docs.txt', $content, 60 * 60 * 24); // Cache for 24 hours
                return $content;
            } else {
                throw new \Exception('Failed to fetch MCP protocol documentation.');
            }
        });
    }

    public function read(): Result
    {
        $doc = Cache::get('mcp://protocol-docs.txt', function() {
            $response = Http::get('https://modelcontextprotocol.io/llms-full.txt');

            if($response->successful()) {
                $content = $response->body();
                Cache::put('mcp://protocol-docs.txt', $content, 60 * 60 * 24); // Cache for 24 hours
                return $content;
            } else {
                throw new \Exception('Failed to fetch MCP protocol documentation.');
            }
        });

        $session = mcp_session()->load(request()->header('mcp-session-id'));
        $response_class = ReadResourceController::getMethodResponseSchema($session->protocolVersion);
        $protocol_ns = str_replace('-', '', $session->protocolVersion);
        $str = "Superconductor\\Schema\\Definitions\\V{$protocol_ns}\\Resources\\";
        $text_content_class = $str . 'TextResourceContents';

        $contents = [
            new $text_content_class(...['uri' => 'mcp://protocol-docs.txt', 'mimeType' => 'text/plain', 'text' => $doc ]),
        ];
        return new $response_class($contents);
    }
}
