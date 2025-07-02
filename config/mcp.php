<?php

return [
    'json-rpc' => [
        'version' => '2.0',
    ],
    'versions' => [
        'latest' => '2024-11-05',
        'default' => '2024-11-05',
        'supported' => [
            '2024-11-05',
        ]
    ],
    'capabilities' => [
        'server' => [
            'experimental' => [
                'enabled' => false,
                'capabilities' => []
            ]
        ],
        'client' => [
            'experimental' => [
                'enabled' => true,
                'capabilities' => []
            ]
        ],
    ]
];
