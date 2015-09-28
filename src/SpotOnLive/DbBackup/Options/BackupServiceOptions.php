<?php

namespace SpotOnLive\DbBackup\Options;

class BackupServiceOptions extends Options
{
    /** @var array */
    protected $defaults = [
        'adapter_chain' => [],
    ];
}
