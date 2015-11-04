<?php

namespace SpotOnLive\DbBackup\Adapters\Dump;

use SpotOnLive\DbBackup\Options\DumpOptions;
use Ifsnop\Mysqldump;

class MySQLDumpAdapter extends DumpAdapter implements DumpAdapterInterface
{
    /** @var DumpOptions */
    protected $options;

    public function __construct(array $config = [])
    {
        $this->options = new DumpOptions($config);
    }

    /**
     * Dump database SQL
     *
     * @param string $database
     * @return string
     * @throws \Exception
     */
    public function dump($database)
    {
        $options = $this->options;
        $port = $options->get('port');
        $host = $options->get('host');
        $username = $options->get('username');
        $password = $options->get('password');

        $temporaryFile = tempnam("/tmp", uniqid());
        $credentialsFile = $this->generateTemporaryCredentialsFile($username, $password);

        $command = sprintf(
            'mysqldump --defaults-extra-file=%s -P %s -h %s %s > %s',
            escapeshellarg($credentialsFile),
            escapeshellarg($port),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($temporaryFile)
        );

        try {
            $this->run(
                $command,
                env('BACKUP_TIMEOUT', 120)
            );

            $this->removeTemporaryCredentialsFile($credentialsFile);
        } catch (\Exception $e) {
            $this->removeTemporaryCredentialsFile($credentialsFile);
            throw $e;
        }

        // Save sql to file
        $content = file_get_contents($temporaryFile);

        // Delete temporary file
        unlink($temporaryFile);

        return $content;
    }

    /**
     * Write temporary credentials file
     *
     * @param string $username
     * @param string $password
     * @return string
     */
    public function generateTemporaryCredentialsFile($username, $password)
    {
        $credentialsFile = tempnam("/tmp", uniqid()) . '.cnf';

        $content = "[client]\n";
        $content .= "user=\"" . $username . "\"\n";
        $content .= "password=\"" . $password . "\"";

        $file = fopen($credentialsFile, 'w+');

        fwrite(
            $file,
            $content
        );

        return $credentialsFile;
    }

    /**
     * Remove temporary credentials file
     *
     * @param string $file
     * @return bool
     */
    public function removeTemporaryCredentialsFile($file)
    {
        return unlink($file);
    }

    /**
     * @return DumpOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param DumpOptions $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}
