<?php

namespace SpotOnLive\DbBackup\Adapters\Backup;

class ChainAdapter implements BackupAdapterInterface
{
    /** @var BackupAdapterInterface[]|array */
    protected $adapters;

    /**
     * @param array|BackupAdapterInterface[] $adapters
     */
    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    /**
     * Run backups
     *
     * @param string|null $data
     * @return bool
     */
    public function backup($data = null)
    {
        foreach ($this->adapters as $adapter) {
            $adapter->backup($data);
        }

        return true;
    }

    /**
     * Add new adapter
     *
     * @param BackupAdapterInterface $adapter
     * @return $this
     */
    public function add(BackupAdapterInterface $adapter)
    {
        $this->adapters[] = $adapter;
        return $this;
    }
}
