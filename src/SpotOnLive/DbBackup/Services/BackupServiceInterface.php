<?php

namespace SpotOnLive\DbBackup\Services;

interface BackupServiceInterface
{
    /**
     * Backup database
     *
     * @param string $database
     * @return bool
     */
    public function backup($database = null);
}
