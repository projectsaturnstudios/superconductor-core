<?php

return [
    'versions' => [
        'active' => '2025-03-26',
        'available' => [
            '2025-03-26' => true,
            '2024-11-05' => true,
        ],
        'add-ons' => [],
    ],
    'endpoints' => [
        'auth_guard' => null,
        'middleware' => [
            'starting' => ['auth:sanctum'],
            'message' => [ //@todo - all middle ware should call an action such that it can be used by web protocols and STDIO
                \Superconductor\Http\Middleware\ValidateIncomingJSONRPCMessage::class,
                \Superconductor\Http\Middleware\DetermineMessageType::class,
                \Superconductor\Http\Middleware\TransformToMCPSchema::class,
                // LogReceivedMessage - requires driver
                \Superconductor\Http\Middleware\AcceptIfNotProcessing::class,
                \Superconductor\Http\Middleware\DispatchMethod::class,
                \Superconductor\Http\Middleware\ReturnMessageIfNotInLoop::class
                // DispatchMessageIfInLoop
                // if it makes it this far the messages controller will return an empty 202
            ],
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
        'add-ons' => [],
    ],

    'looping_mechanism' => [
        'default' => 'react',
        'available' => [
            'blocking' => [
                'should_sleep' => true, // whether the loop should sleep between iterations
                'sleep_time' => 100000, // microseconds,
                'max_execution_time' => 0, // 0 means no limit
            ],
            'react' => [
                'sleep_time' => 0.1, // seconds
                'max_execution_time' => 0, // 0 means no limit
            ],
        ],
        'add-ons' => [],
    ],

    'outgoing_message_dispatcher' => [
        'default' => 'events',
        'available' => [
            'cached' => [],
            'events' => [],
        ],
        'add-ons' => [],
    ],

    'features' => [
        'methods' => [
            'registrar' => [
                'default' => 'hardcoded',
                'available' => [
                    'hardcoded' => [
                        'initialize' => \Superconductor\Methods\InitializeMethod::class,
                        'ping' => \Superconductor\Methods\PingMethod::class,
                        'tools/list' => \Superconductor\Methods\Tools\ListToolsMethod::class,
                        'tools/call' => \Superconductor\Methods\Tools\ToolCallMethod::class,
                        'resources/list' => \Superconductor\Methods\Resources\ListResourcesMethod::class,
                        'resources/read' => \Superconductor\Methods\Resources\ReadResourceMethod::class,
                        'prompts/list' => \Superconductor\Methods\Prompts\ListPromptsMethod::class,
                        'prompts/get' => \Superconductor\Methods\Prompts\GetPromptMethod::class,
                    ],
                    'routing' => [],
                    'agency' => [
                        'auto_discover_methods' => [app()->path(),],
                        'auto_discover_base_path' => base_path(),
                        'methods' => [
                            'initialize' => ''
                        ]
                    ]
                ],
                'add-ons' => [],
            ],
        ],
        'capabilities' => [
            'registrar' => [
                'default'=> 'hardcoded',
                'available' => [
                    'hardcoded' => [
                        'tools' => [
                            'echo' => \Superconductor\Capabilities\Tools\EchoTool::class,
                            'list_commands' => \Superconductor\Capabilities\Tools\ListCommandsTool::class
                        ],
                        'resources' => [
                            'mcp://protocol-docs.txt' => \Superconductor\Capabilities\Resources\MCPProtocolDocsResource::class,
                        ],
                        'resource_templates' => [],
                        'prompts' => [
                            'some-vague-prompt' => \Superconductor\Capabilities\Prompts\SomeVaguePrompt::class,
                        ],
                    ],
                    'routing' => [],
                    'agency' => [
                        'auto_discover_methods' => [app()->path(),],
                        'auto_discover_base_path' => base_path(),
                        'methods' => [
                            'initialize' => ''
                        ]
                    ]
                ],
                'add-ons' => [],
            ],
        ]
    ],

    'processing' => [
        'process_results' => false,
        'process_notifications' => false,

    ],
    'server' => [
        'details' => [
            'name' => env('MCP_SERVER', 'Superconductor'),
            'version' => env('MCP_SERVER_VERSION', '0.0.1'),
        ],
    ]
];
