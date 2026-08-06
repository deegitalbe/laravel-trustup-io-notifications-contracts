<?php

declare(strict_types=1);

return [
    'kafka' => [
        'brokers' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_BROKERS', 'localhost:9092'),
        'security_protocol' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_SECURITY_PROTOCOL', 'PLAINTEXT'),
        'sasl' => [
            'mechanisms' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_MECHANISMS'),
            'username' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_USERNAME'),
            'password' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_PASSWORD'),
        ],
        'consumer_group_id' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_CONSUMER_GROUP_ID', 'trustup-io-notifications'),
        'compression' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_COMPRESSION', 'lz4'),
        'offset_reset' => env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_OFFSET_RESET', 'latest'),
        'message_max_bytes' => (int) env('TRUSTUP_IO_NOTIFICATIONS_KAFKA_MESSAGE_MAX_BYTES', 1048576),
    ],

    'topics' => [
        'request' => env('TRUSTUP_IO_NOTIFICATIONS_TOPIC_REQUEST', 'notifications.request'),
        'status' => env('TRUSTUP_IO_NOTIFICATIONS_TOPIC_STATUS', 'notifications.status'),
        'engagement' => env('TRUSTUP_IO_NOTIFICATIONS_TOPIC_ENGAGEMENT', 'notifications.engagement'),
        'dlq' => env('TRUSTUP_IO_NOTIFICATIONS_TOPIC_DLQ', 'notifications.dlq'),
    ],
];
