<?php

namespace SpotOnLive\DbBackup\Options;

class FileAdapterOptions extends Options
{
    /** @var array */
    protected $defaults = [
        'storage_path' => '../storage/',
        'prefix' => null,
        'file_type' => 'sql'
    ];
}
