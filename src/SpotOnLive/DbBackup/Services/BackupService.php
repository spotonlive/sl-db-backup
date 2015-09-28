<?php

namespace SpotOnLive\DbBackup\Services;

use SpotOnLive\DbBackup\Adapters\Backup\BackupAdapterInterface;
use SpotOnLive\DbBackup\Adapters\Backup\ChainAdapter;
use SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface;
use SpotOnLive\DbBackup\Exceptions\RuntimeException;
use SpotOnLive\DbBackup\Options\BackupServiceOptions;

class BackupService implements BackupServiceInterface
{
    /** @var BackupAdapterInterface */
    protected $backupAdapter;

    /** @var DumpAdapterInterface */
    protected $dumpAdapter;

    /** @var BackupServiceOptions */
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = new BackupServiceOptions($options);
        $this->attachDumpAdapter();
        $this->attachAdapters();
    }

    /**
     * Backup
     *
     * @param null $database
     * @return bool
     */
    public function backup($database = null)
    {
        if (is_null($database)) {
            $database = env('DB_DATABASE');
        }

        $content = $this->dumpAdapter->dump($database);

        $this->backupAdapter->backup($content);

        return true;
    }

    /**
     * Attach adapters
     *
     * @return bool
     * @throws RuntimeException
     */
    protected function attachAdapters()
    {
        $options = $this->options;
        $adapterChain = $options->get('adapter_chain');

        $chain = new ChainAdapter();

        foreach($adapterChain as $adapterConfig) {
            if (!isset($adapterConfig['adapter']) || !class_exists($adapterConfig['adapter'])) {
                throw new RuntimeException(
                    _('Please provide an adapter class')
                );
            }

            $adapter = new $adapterConfig['adapter']($adapterConfig['config']);

            if (!$adapter instanceof BackupAdapterInterface) {
                throw new RuntimeException(
                    _('Please provide a valid backup adapter')
                );
            }

            $chain->add($adapter);
        }

        $this->backupAdapter = $chain;

        return true;
    }

    /**
     * Attach dump adapter
     *
     * @throws RuntimeException
     */
    protected function attachDumpAdapter()
    {
        $options = $this->options->get('dump');

        if (!isset($options['adapter']) || is_null($options['adapter']) || !class_exists($options['adapter'])) {
            throw new RuntimeException(
                _('Please provide an adapter class')
            );
        }

        $adapter = new $options['adapter']($options['config']);

        if (!$adapter instanceof DumpAdapterInterface) {
            throw new RuntimeException(
                _('Please provide a valid adapter class')
            );
        }

        $this->dumpAdapter = $adapter;
    }

    /**
     * Set adapter
     *
     * @param BackupAdapterInterface $adapter
     */
    public function setAdapter($adapter)
    {
        $this->backupAdapter = $adapter;
    }
}
