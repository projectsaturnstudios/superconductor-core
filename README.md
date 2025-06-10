# Superconductor Core


[![Latest Version on Packagist](https://img.shields.io/packagist/v/projectsaturnstudios/superconductor-core.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/superconductor-core)
[![Total Downloads](https://img.shields.io/packagist/dt/projectsaturnstudios/superconductor-core.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/superconductor-core)

**Transform any Laravel 12+ application into a powerful Model Context Protocol (MCP) server with elegant Laravel patterns.**

---

Each GitHub ⭐️ helps the community grow. Thanks for the support!

---

**NOTE! AI wrote this documentation, it's mostly solid and factual, but a human generated README will be available soon!**

## Table of Contents

- [🎯 Introduction](#introduction)
- [✨ Features](#features)
- [📅 Roadmap](#roadmap)
- [🚀 Getting Started](#getting-started)
  - [Requirements](#requirements)
  - [Installation](#installation)
  - [Configuration](#configuration)
- [📖 Core Concepts](#core-concepts)
  - [Schema & Protocol Versions](#schema--protocol-versions)
  - [Session Management](#session-management)
  - [Capability Registration](#capability-registration)
- [🛠️ Usage](#usage)
  - [Getting Started with Defaults](#getting-started-with-defaults)
  - [Creating Tools](#creating-tools)
  - [Working with Resources](#working-with-resources)
  - [Building Prompts](#building-prompts)
- [📋 Implemented Methods](#implemented-methods)
- [🔌 Transport Integration](#transport-integration)
- [🧪 Testing](#testing)
- [🤝 Contributing](#contributing)
- [🔒 Security](#security)
- [📜 License](#license)

## Introduction

Superconductor Core brings the Model Context Protocol to Laravel with an architecture that feels native to the framework. Create AI-powered tools, expose resources, and build prompts using familiar Laravel patterns with Laravel Data for type safety and automatic schema generation.

Creating MCP capabilities follows familiar Laravel patterns using attributes and class registration:

```php
namespace App\MCP\Tools;

use Superconductor\Capabilities\Tools\BaseTool;
use Superconductor\Support\Attributes\ToolCall;

#[ToolCall(
    tool: 'calculator',
    description: 'Calculate the sum of two numbers',
    inputSchema: [
        'type' => 'object',
        'properties' => [
            'a' => ['type' => 'number', 'description' => 'First number'],
            'b' => ['type' => 'number', 'description' => 'Second number']
        ],
        'required' => ['a', 'b']
    ]
)]
class CalculatorTool extends BaseTool
{
    public function handle($incoming_message): array
    {
        $results = [
            'content' => [],
            'isError' => false
        ];

        if ($content = $this->execute(...$incoming_message->params['arguments'])) {
            $text_content_class = $incoming_message::getTextContextObj();
            $text_content = new $text_content_class($content);
            $results['content'][] = $text_content->toValue();
        }

        return $results;
    }

    private function execute(int $a, int $b): string
    {
        return "The sum of {$a} + {$b} = " . ($a + $b);
    }
}
```

Register it in your config:

```php
// config/mcp.php - Update the capabilities.hardcoded.tools array
'features' => [
    'capabilities' => [
        'registrar' => [
            'available' => [
                'hardcoded' => [
                    'tools' => [
                        'calculator' => App\MCP\Tools\CalculatorTool::class,
                    ],
                    // ... other capabilities
                ],
            ],
        ],
    ],
],
```

Your Laravel app is now MCP-capable via transport layers!

## Features

- **Laravel Data Integration** - Type-safe schema objects with automatic JSON-RPC serialization
- **Multi-Protocol Support** - Compatible with MCP versions 2024-11-05 and 2025-03-26
- **Transport Agnostic** - Core logic works with any transport layer (HTTP, SSE, STDIO)
- **Elegant API Design** - Familiar Laravel patterns with Facades, Actions, and Service Providers
- **Comprehensive Capabilities** - Full support for Tools, Resources, and Prompts
- **Flexible Session Management** - Cache-based and database drivers included
- **Zero-Configuration Start** - Works out of the box with sensible defaults
- **Attribute-Based Registration** - Clean, declarative capability definitions
- **Laravel Actions Integration** - Built with Lorisleiva Laravel Actions for clean architecture
- **Spatie Laravel Data** - Type-safe data objects with automatic validation

## Roadmap

Here's what's coming next to make Superconductor even more powerful:

### Enhanced Developer Experience 🛠️
- **Artisan Commands for Rapid Development** (Planned)
  - `make:mcp-tool` - Generate tool classes with ready-to-use stubs
  - `make:mcp-resource` - Create resource classes with proper structure
  - `make:mcp-prompt` - Scaffold prompt implementations
  - `mcp:list-capabilities` - List all registered capabilities
  - `mcp:validate-config` - Validate MCP configuration

### Advanced Capabilities 🧠
- **Dynamic Capability Discovery** - Runtime capability registration
- **Enhanced Resource Templates** - URI template support with dynamic parameters
- **Batch Operations** - Execute multiple tools in parallel
- **Streaming Support** - Real-time streaming for long-running operations
- **Capability Metadata** - Rich annotations and documentation

### Additional Transports 🔌
- **STDIO Transport** - For local development and CLI integration (Not Ready Yet)
- **StreamableHttp Transport** - For Local & Remote HTTP-based communication (Ready!)


Stay tuned! We're constantly working on making Superconductor the most versatile MCP framework for Laravel.

## Getting Started

### Requirements

- PHP 8.2 or higher
- Laravel 11.x or higher
- Spatie Laravel Data ^4.11
- Lorisleiva Laravel Actions ^2.6

### Installation

Install the package via Composer:

```bash
composer require projectsaturnstudios/superconductor-core
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=mcp 
```

The package will automatically register its service providers and you're ready to go!

### Configuration

The package works with zero configuration, but you can customize it by editing `config/mcp.php`:

```php
<?php

return [
    'versions' => [
        'active' => '2025-03-26',
        'available' => [
            '2025-03-26' => true,
            '2024-11-05' => true,
        ],
    ],

    'session_managers' => [
        'session_class' => \Superconductor\Sessions\MCPSessionObject::class,
        'default' => 'cached',
        'available' => [
            'cached' => [
                'sessions_expire_in' => 300, // minutes
            ],
            'database' => [],
        ],
    ],

    'features' => [
        'capabilities' => [
            'registrar' => [
                'default' => 'hardcoded',
                'available' => [
                    'hardcoded' => [
                        'tools' => [
                            'echo' => \Superconductor\Capabilities\Tools\EchoTool::class,
                            'list_commands' => \Superconductor\Capabilities\Tools\ListCommandsTool::class,
                            // Remove the above tools and Add your custom tools here
                        ],
                        'resources' => [
                            'mcp://protocol-docs.txt' => \Superconductor\Capabilities\Resources\MCPProtocolDocsResource::class,
                            // Remove the above resources Add your custom resources here
                        ],
                        'prompts' => [
                            'some-vague-prompt' => \Superconductor\Capabilities\Prompts\SomeVaguePrompt::class,
                            // Remove the above prompts Add your custom prompts here
                        ],
                    ],
                ],
            ],
        ],
    ],

    'server' => [
        'details' => [
            'name' => env('MCP_SERVER', 'Superconductor'),
            'version' => env('MCP_SERVER_VERSION', '0.0.1'),
        ],
    ],
];
```

## Core Concepts

### Schema & Protocol Versions

Superconductor automatically handles multiple MCP protocol versions using Laravel Data objects. Access schema information using the available facades:

```php
use Superconductor\Support\Facades\SchemaManager;

// Get the active protocol version
$version = config('mcp.versions.active'); // '2025-03-26'

// Check available versions
$available = config('mcp.versions.available');
// ['2025-03-26' => true, '2024-11-05' => true]

// Schema objects are automatically selected based on protocol version
// Tools, Resources, and Prompts use version-specific Data classes
```

Protocol versions are handled through inheritance in the Schema namespace:

```php
// Superconductor\Schema\Definitions\V20250326\Tools\Tool 
// extends Superconductor\Schema\Definitions\V20241105\Tools\Tool
// This ensures backward compatibility while adding new features
```

### Session Management

Sessions maintain state between MCP interactions. The **default driver uses Laravel's native cache**:

```php
use Superconductor\Support\Facades\SessionManager;

// Using the helper function (recommended)
generate_mcp_session('user-123');

// Or via the Facade
$session = SessionManager::createOrLoad('user-123');

// Sessions are automatically created and managed
// The session object extends AbstractMCPSession
```

Session drivers available:
- **`cached`** (default) - Uses Laravel's cache system with 300-minute expiry
- **`database`** - Stores sessions in database (requires migration)

### Capability Registration

The **default hardcoded driver** registers capabilities from your config file. To add new capabilities:

1. **Publish the MCP config:**
   ```bash
   php artisan vendor:publish --tag=mcp
   ```

2. **Add your capability classes to the nested config structure:**
   ```php
   // config/mcp.php
   'features' => [
       'capabilities' => [
           'registrar' => [
               'available' => [
                   'hardcoded' => [
                       'tools' => [
                           'calculator' => App\MCP\Tools\CalculatorTool::class,
                           'weather' => App\MCP\Tools\WeatherTool::class,
                       ],
                       'resources' => [
                           'mcp://database' => App\MCP\Resources\DatabaseResource::class,
                           'mcp://logs' => App\MCP\Resources\LogsResource::class,
                       ],
                       'prompts' => [
                           'welcome' => App\MCP\Prompts\WelcomePrompt::class,
                       ],
                   ],
               ],
           ],
       ],
   ],
   ```

3. **Ensure your classes use the required attributes** (see Usage section below)

## Usage

### Getting Started with Defaults

Superconductor works immediately with sensible defaults:

```php
// Create a session (uses cached driver by default)
generate_mcp_session('demo-session');

// Get protocol version (from config, defaults to 2025-03-26)
$version = config('mcp.versions.active');

// List available capabilities using helper functions
$tools = list_tools();
$resources = list_resources();
$prompts = list_prompts();

// Or specify a protocol version
$tools = list_tools('2024-11-05');
```

The **Laravel cache** is used as the default session storage, and the **hardcoded driver** loads capabilities from your config file.

### Creating Tools

Tools are the primary way to expose functionality. Use the `#[ToolCall]` attribute and extend the `BaseTool` class:

```php
namespace App\MCP\Tools;

use Superconductor\Capabilities\Tools\BaseTool;
use Superconductor\Support\Attributes\ToolCall;

#[ToolCall(
    tool: 'weather_lookup',
    description: 'Get current weather for a location',
    inputSchema: [
        'type' => 'object',
        'properties' => [
            'location' => ['type' => 'string', 'description' => 'City name'],
            'unit' => ['type' => 'string', 'enum' => ['celsius', 'fahrenheit'], 'default' => 'celsius']
        ],
        'required' => ['location']
    ]
)]
class WeatherTool extends BaseTool
{
    public function handle($incoming_message): array
    {
        $results = [
            'content' => [],
            'isError' => false
        ];

        if ($content = $this->execute(...$incoming_message->params['arguments'])) {
            $text_content_class = $incoming_message::getTextContextObj();
            $text_content = new $text_content_class($content);
            $results['content'][] = $text_content->toValue();
        }

        return $results;
    }
    
    private function execute(string $location, string $unit = 'celsius'): string
    {
        // Your implementation here
        $weather = $this->fetchWeatherData($location, $unit);
        
        return "Weather in {$location}: {$weather['temperature']}°{$unit[0]} - {$weather['condition']}";
    }
    
    private function fetchWeatherData(string $location, string $unit): array
    {
        // Integrate with your weather API
        return [
            'temperature' => 22,
            'condition' => 'sunny',
        ];
    }
}
```

**Advanced Tool Example:**

```php
#[ToolCall(
    tool: 'database_query',
    description: 'Execute safe database queries',
    inputSchema: [
        'type' => 'object',
        'properties' => [
            'table' => ['type' => 'string', 'description' => 'Table name'],
            'conditions' => ['type' => 'object', 'description' => 'Query conditions'],
            'limit' => ['type' => 'integer', 'default' => 10, 'maximum' => 100]
        ],
        'required' => ['table']
    ]
)]
class DatabaseQueryTool extends BaseTool
{
    public function handle($incoming_message): array
    {
        $results = [
            'content' => [],
            'isError' => false
        ];

        try {
            $content = $this->execute(...$incoming_message->params['arguments']);
            $text_content_class = $incoming_message::getTextContextObj();
            $text_content = new $text_content_class(json_encode($content, JSON_PRETTY_PRINT));
            $results['content'][] = $text_content->toValue();
        } catch (\Exception $e) {
            $results['isError'] = true;
            $text_content_class = $incoming_message::getTextContextObj();
            $text_content = new $text_content_class("Error: " . $e->getMessage());
            $results['content'][] = $text_content->toValue();
        }

        return $results;
    }
    
    private function execute(string $table, array $conditions = [], int $limit = 10): array
    {
        // Validate table name against whitelist
        $allowedTables = ['users', 'posts', 'comments'];
        if (!in_array($table, $allowedTables)) {
            throw new \InvalidArgumentException("Table '{$table}' is not allowed");
        }
        
        $query = DB::table($table)->limit($limit);
        
        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->get()->toArray();
    }
}
```

### Working with Resources

Resources expose readable data using the `#[ReadableResource]` attribute:

```php
namespace App\MCP\Resources;

use Superconductor\Capabilities\Resources\BaseResource;
use Superconductor\Support\Attributes\ReadableResource;

#[ReadableResource(
    uri: 'mcp://application-logs',
    name: 'Application Logs',
    description: 'Recent application log entries',
    mimeType: 'text/plain'
)]
class LogsResource extends BaseResource
{
    public function handle($incoming_message): array
    {
        $results = [
            'contents' => [],
        ];

        if ($content = $this->execute()) {
            $uri = $this->getResourceAttribute()->uri;
            $text_content_class = $incoming_message::getTextResourceContextObj();
            $text_content = new $text_content_class($uri, $content, 'text/plain');
            $results['contents'][] = $text_content->toValue();
        }

        return $results;
    }
    
    private function execute(): string
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return 'No log file found';
        }
        
        // Return last 100 lines
        $lines = file($logFile);
        $recentLines = array_slice($lines, -100);
        
        return implode('', $recentLines);
    }
}
```

**Dynamic Resource Example:**

```php
#[ReadableResource(
    uri: 'database://users/{id}',
    name: 'User Profile',
    description: 'User profile information'
)]
class UserProfileResource extends Resource
{
    public function read(int $id): array
    {
        $user = User::findOrFail($id);
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toISOString(),
            'profile' => $user->profile?->toArray(),
        ];
    }
    
    public function getMimeType(): string
    {
        return 'application/json';
    }
}
```

### Building Prompts

Prompts provide reusable templates with argument support:

```php
namespace App\MCP\Prompts;

use Superconductor\Capabilities\Prompts\BasePrompt;
use Superconductor\Support\Attributes\ActionablePrompt;

#[ActionablePrompt(
    name: 'welcome_user',
    description: 'Welcome new users to the application',
    arguments: [
        [
            'name' => 'name',
            'description' => 'The user\'s name',
            'required' => true,
        ],
        [
            'name' => 'role',
            'description' => 'The user\'s role in the system',
            'required' => false,
        ],
    ]
)]
class WelcomePrompt extends BasePrompt
{
    public function handle($incoming_message): array
    {
        $results = [
            'description' => 'Welcome message for new users',
            'messages' => []
        ];
        
        if ($content = $this->execute(...$incoming_message->params['arguments'])) {
            $results['messages'] = $content;
        }

        return $results;
    }
    
    private function execute(string $name, ?string $role = null): array
    {
        $greeting = "Welcome to our application, {$name}!";
        
        if ($role) {
            $greeting .= " You're logged in as {$role}.";
        }
        
        $greeting .= "\n\nHere are some things you can do:\n";
        $greeting .= "- Check your dashboard\n";
        $greeting .= "- Update your profile\n";
        $greeting .= "- Explore our features";
        
        return [
            [
                'role' => 'assistant',
                'content' => [
                    'type' => 'text',
                    'text' => $greeting,
                ]
            ]
        ];
    }
}
```

**Complex Prompt Example:**

```php
#[ActionablePrompt(
    name: 'code_review',
    description: 'Generate code review guidelines',
    arguments: [
        [
            'name' => 'language',
            'description' => 'Programming language',
            'required' => true,
        ],
        [
            'name' => 'complexity',
            'description' => 'Code complexity level (simple, medium, complex)',
            'required' => false,
        ],
    ]
)]
class CodeReviewPrompt extends BasePrompt
{
    public function handle($incoming_message): array
    {
        $results = [
            'description' => 'Code review guidelines',
            'messages' => []
        ];
        
        if ($content = $this->execute(...$incoming_message->params['arguments'])) {
            $results['messages'] = $content;
        }

        return $results;
    }
    
    private function execute(string $language, string $complexity = 'medium'): array
    {
        $guidelines = $this->getBaseGuidelines($language);
        $specific = $this->getComplexityGuidelines($complexity);
        
        $text = "# Code Review Guidelines for {$language}\n\n" .
               "## General Guidelines\n{$guidelines}\n\n" .
               "## {$complexity} Complexity Considerations\n{$specific}";
        
        return [
            [
                'role' => 'assistant',
                'content' => [
                    'type' => 'text',
                    'text' => $text,
                ]
            ]
        ];
    }
    
    private function getBaseGuidelines(string $language): string
    {
        return match($language) {
            'php' => "- Follow PSR standards\n- Check for proper error handling\n- Verify input validation",
            'javascript' => "- Check for proper async/await usage\n- Verify error boundaries\n- Review type safety",
            default => "- Follow language conventions\n- Check for proper error handling\n- Verify code clarity",
        };
    }
    
    private function getComplexityGuidelines(string $complexity): string
    {
        return match($complexity) {
            'simple' => "- Focus on readability\n- Check basic functionality",
            'complex' => "- Review architecture decisions\n- Check performance implications\n- Verify testing coverage",
            default => "- Balance readability and functionality\n- Check for proper abstractions",
        };
    }
}
```

## Implemented Methods

Superconductor Core includes implementations for core MCP methods:

### Server Information
- **`initialize`** - Initialize the MCP connection and establish protocol version
- **`ping`** - Health check endpoint for connection verification

### Tool Operations
- **`tools/list`** - List all available tools with their schemas and descriptions
- **`tools/call`** - Execute a specific tool with provided arguments

### Resource Operations  
- **`resources/list`** - List all available resources with their URIs and metadata
- **`resources/read`** - Read content from a specific resource URI

### Prompt Operations
- **`prompts/list`** - List all available prompts with their argument schemas
- **`prompts/get`** - Retrieve a prompt with specific arguments and generate messages

### Helper Functions
The package provides convenient helper functions for capability access:

```php
// List capabilities using helper functions
$tools = list_tools();           // Get all available tools
$resources = list_resources();   // Get all available resources  
$prompts = list_prompts();       // Get all available prompts

// Specify protocol version
$tools = list_tools('2024-11-05');
```

All methods return properly typed Laravel Data objects that automatically serialize to valid JSON-RPC responses according to the configured MCP protocol version.

## Transport Integration

Superconductor Core is designed to be **transport-agnostic**. The core logic works with any transport layer through a clean interface.

### Available Transports

| Transport | Package | Status | Description                               |
|-----------|---------|---------|-------------------------------------------|
| **Streamable HTTP** | `superconductor-streamable-http` | ✅ Available | HTTP POST + optional SSE (SSE incomplete) |
| **STDIO** | `superconductor-stdio` | 🚧 Coming Soon | Standard input/output                     |

### Installing a Transport

```bash
# Install HTTP transport
composer require superconductor-mcp/streamable-http
```

Transport packages integrate automatically with Superconductor Core through the middleware pipeline and method registration system.

## Testing

Test your MCP capabilities using standard Laravel testing patterns:

```php
use Tests\TestCase;

class MCPCapabilityTest extends TestCase
{
    public function test_tool_execution()
    {
        // Test your tools using the helper functions
        $tools = list_tools();
        $this->assertNotEmpty($tools);
        
        // Test specific tool functionality
        // Implementation depends on your transport layer
    }
    
    public function test_resource_access()
    {
        $resources = list_resources();
        $this->assertNotEmpty($resources);
    }
    
    public function test_prompt_generation()
    {
        $prompts = list_prompts();
        $this->assertNotEmpty($prompts);
    }
}

## Contributing

We welcome contributions to Superconductor! Whether it's improving documentation, fixing bugs, or adding new features, your help is appreciated.

### Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`
4. Run static analysis: `composer analyse`

### Contribution Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Ensure backward compatibility

## Security

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

### Security Considerations

- **Validate all tool inputs** - Never trust user-provided arguments
- **Implement proper authorization** - Check user permissions before executing tools
- **Sanitize resource access** - Prevent path traversal and unauthorized file access
- **Rate limiting** - Implement appropriate rate limits for resource-intensive operations

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

**Ready to supercharge your Laravel app with MCP?**

```bash
composer require projectsaturnstudios/superconductor-core
composer require superconductor-mcp/streamable-http
```

*Your Laravel application is now AI-ready! 🚀* 
