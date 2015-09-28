<?php

namespace SpotOnLive\DbBackup\Adapters\Backup;

use SpotOnLive\DbBackup\Exceptions\PermissionException;
use SpotOnLive\DbBackup\Exceptions\RuntimeException;
use SpotOnLive\DbBackup\Options\FileAdapterOptions;

class FileAdapter implements BackupAdapterInterface
{
    /** @var FileAdapterOptions */
    protected $options;

    public function __construct(array $options)
    {
        $this->options = new FileAdapterOptions($options);
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
        $location = $this->getStoragePath() . $this->getFilename();

        $backup = file_put_contents(
            $location,
            $data
        );

        if (!$backup) {
            throw new RuntimeException(
                sprintf(
                    _('The backup could not be saved at \'%s\''),
                    $location
                )
            );
        }

        return true;
    }

    /**
     * Get new filename
     *
     * @return string
     */
    public function getFilename()
    {
        $options = $this->options;
        return $options->get('prefix') . time() . '.' . $options->get('file_type');
    }

    /**
     * Get storage path
     *
     * @return string
     * @throws PermissionException
     */
    public function getStoragePath()
    {
        $options = $this->options;
        $storagePath = $options->get('storage_path');

        if (!is_writable(dirname($storagePath))) {
            throw new PermissionException(
                sprintf(
                    _('Backup directory \'%s\' must be writeable'),
                    $storagePath
                )
            );
        }

        return $storagePath;
    }
}
