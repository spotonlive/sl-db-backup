<?php

namespace SpotOnLive\DbBackup\Adapters\Backup;

use SpotOnLive\DbBackup\Exceptions\PermissionException;
use SpotOnLive\DbBackup\Exceptions\RuntimeException;
use SpotOnLive\DbBackup\Options\AmazonAdapterOptions;

class AmazonAdapter implements BackupAdapterInterface
{
    /** @var AmazonAdapterOptions */
    protected $options;

    public function __construct(array $options)
    {
        $this->options = new AmazonAdapterOptions($options);
    }

    /**
     * Run backup
     *
     * @param null|string $data
     * @return bool
     * @throws PermissionException
     * @throws RuntimeException
     */
    public function backup($data = null)
    {
    }
}