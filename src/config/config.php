<?php

return [
    // Dump adapter
    'dump' => [
        'adapter' => 'SpotOnLive\DbBackup\Adapters\Dump\MySQLDumpAdapter',

        'config' => [
            'host' => env('DB_HOST', 'localhost'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ],
    ],

    // Backup adapters
    'adapter_chain' => [
        /*
         * Examples
         *
        [
            // File system

            'adapter' => 'SpotOnLive\DbBackup\Adapters\Backup\FileAdapter',

            'config' => [
                'storage_path' => storage_path('/'),
                'prefix' => 'backup_',
                'file_type' => 'sql',
             ],
        ],
        [
            // Amazon

            'adapter' => 'SpotOnLive\DbBackup\Adapters\Backup\AmazonAdapter',

            'config' => [
                // Path to directory in AWS S3
                'storage_path' => '',
                'prefix' => 'backup_',
                'file_type' => 'sql',

                'credentials' => [
                    'key'    => env('AWS_KEY'),
                    'secret' => env('AWS_SECRET'),
                    'region' => env('AWS_REGION'),
                    'bucket' => env('AWS_BUCKET'),
                ],
            ],
        ],
        */
    ]
];
