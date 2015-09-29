<?php

namespace SpotOnLive\DbBackup\Adapters\Dump;

use SpotOnLive\DbBackup\Options\DumpOptions;
use Ifsnop\Mysqldump;

class MySQLDumpAdapter implements DumpAdapterInterface
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
        $host = $options->get('host');
        $username = $options->get('username');
        $password = $options->get('password');

        // Dump database to file
        $dumper = new Mysqldump\Mysqldump(
            'mysql:host=' . $host . ';dbname=' . $database,
            $username,
            $password
        );

        $temporaryFile = tempnam("/tmp", uniqid());

        $dumper->start($temporaryFile);

        // Save sql to file
        $content = file_get_contents($temporaryFile);

        // Delete temporary file
        unlink($temporaryFile);

        return $content;
    }
}
