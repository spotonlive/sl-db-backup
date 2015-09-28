<?php

namespace SpotOnLive\DbBackup\Adapters\Dump;

interface DumpAdapterInterface
{
    /**
     * Dump database
     *
     * @param string $database
     * @return string
     */
    public function dump($database);
}
