<?php

return [
    'sse' => [],
    'stdio' => [
        'laravel-vibes' => [
            "command" => "php",
            "args" => ["artisan","stdio:serve", "vibes"],
            'env' => [],
        ]
    ],
    'streamable' => [
        'laravel-vibes' => [
            "url" => env("APP_URL")."/api/streamable?using=vibes",
            "headers" => [
                "Authorization" => "auth_token"
            ],
        ]
    ],
];
