<?php

return [


    // Backup services
    'adapter_chain' => [
        /*
         * Examples
         *
        [
            // File system
            'name' => 'file',

            'adapter' => 'SpotOnLive\DbBackup\Adapters\Backup\File',

            'config' => [
                'storage_path' => './storage/',
                'prefix' => 'backup_',
                'file_type' => 'sql',
             ],
        ],
        [
            // Amazon
            'name' => 's3',
            'adapter' => 'SpotOnLive\DbBackup\Adapters\Backup\Amazon',

            'config' => [
                'storage_path' => './backups/',
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
