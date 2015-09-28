<?php

namespace SpotOnLive\DbBackup\Adapters\Backup;

interface BackupAdapterInterface
{
    /**
     * Create new backup
     *
     * @param null $data Backup data
     * @return boolean|array|string
     */
    public function backup($data = null);
}
