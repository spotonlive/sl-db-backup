<?php

namespace SpotOnLive\DbBackup\Adapters\Dump;

use SpotOnLive\DbBackup\Exceptions\RuntimeException;
use Symfony\Component\Process\Process;

class DumpAdapter
{
    /**
     * Run console command
     *
     * @param string $command
     * @param int $timeout
     * @return bool
     * @throws RuntimeException
     */
    public function run($command, $timeout = 120)
    {
        $process = new Process($command);
        $process->setTimeout($timeout);

        // Execute
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                sprintf('Backup command failed: %s', $process->getErrorOutput())
            );
        }

        return true;
    }
}
