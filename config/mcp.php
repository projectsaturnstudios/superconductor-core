<?php

return [
    'server_info' => [
        'name' => 'Superconductor MCP',
        'version' => '0.1.0',
    ],
    'transports' => [
        'stdio' => [
            'enabled' => true,
            'driver' => 'superconductor',
            'drivers' => [
                'superconductor' => [
                    'class' => \Superconductor\Stdio\Drivers\StdioTransportProtocolDriver::class,
                ],
            ]
        ],
        'http-sse' => [
            'enabled' => true,
            'driver' => 'superconductor',
            'drivers' => [
                'superconductor' => [
                    'class' => \Superconductor\Sse\Drivers\SSETransportProtocolDriver::class,
                ],
            ]
        ],
    ],
    'capabilities' => [
        'client' => [
            'roots' => [
                'enabled' => false,
            ],
            'sampling' => [
                'enabled' => false,
            ],
            'experimental' => [
                'enabled' => false,
            ],
        ],
        'server' => [
            'prompts' => [
                'enabled' => false,

            ],
            'resources' => [
                'enabled' => false,
            ],
            'tools' => [
                'enabled' => false,
                'test_tools' => true,
            ],
            'logging' => [
                'enabled' => false,
            ],
            'experimental' => [
                'enabled' => false,
            ],
        ],
    ],
    'sessions' => [
        'default' => 'cached',
        'loop_time' => 100000
    ],
];
