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
            'name' => 'file',

            'adapter' => 'SpotOnLive\DbBackup\Adapters\Backup\FileAdapter',

            'config' => [
                'storage_path' => storage_path('/'),
                'prefix' => 'backup_',
                'file_type' => 'sql',
             ],
        ],
        [
            // Amazon
            'name' => 's3',
            'adapter' => 'SpotOnLive\DbBackup\Adapters\Backup\AmazonAdapter',

            'config' => [
                'storage_path' => storage_path('/'),
                'prefix' => 'backup_',
                'file_type' => 'sql',

                'credentials' => [
                   'key'    => 'your-key',
                   'secret' => 'your-secret',
                   'region' => 'your-region',
                   'bucket' => 'your-bucket',
                ],
            ],
        ],
        */
    ]
];
