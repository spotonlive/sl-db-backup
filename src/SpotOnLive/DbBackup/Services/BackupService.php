<?php

namespace SpotOnLive\DbBackup\Services;

use SpotOnLive\DbBackup\Adapters\Backup\BackupAdapterInterface;
use SpotOnLive\DbBackup\Adapters\Backup\ChainAdapter;
use SpotOnLive\DbBackup\Exceptions\RuntimeException;
use SpotOnLive\DbBackup\Options\BackupServiceOptions;

class BackupService implements BackupServiceInterface
{
    /** @var BackupAdapterInterface */
    protected $adapter;

    /** @var BackupServiceOptions */
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = new BackupServiceOptions($options);
        $this->attachAdapters();
    }

    /**
     * Back up
     */
    public function backup()
    {
        $file = 'test content';
        $this->adapter->backup($file);
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

        $this->adapter = $chain;

        return true;
    }

    /**
     * Set adapter
     *
     * @param BackupAdapterInterface $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
}
