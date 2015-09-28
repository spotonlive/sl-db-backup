<?php

namespace SpotOnLive\DbBackup\Options;

class AmazonAdapterOptions extends Options
{
    /** @var array */
    protected $defaults = [
        'storage_path' => '../storage/',
        'prefix' => null,
        'file_type' => 'sql',

        'credentials' => [
           'key'    => null,
           'secret' => null,
           'region' => null,
           'bucket' => null,
        ],
    ];
}
