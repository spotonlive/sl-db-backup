<?php

namespace SpotOnLive\DbBackup\Adapters\Backup;

use SpotOnLive\DbBackup\Exceptions\PermissionException;
use SpotOnLive\DbBackup\Exceptions\RuntimeException;
use SpotOnLive\DbBackup\Options\FileAdapterOptions;
use DateTime;

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
        return $options->get('prefix') . uniqid($this->getDate()) . '.' . $options->get('file_type');
    }

    /**
     * Get datetime stamp
     *
     * @return string
     */
    public function getDate()
    {
        $dateTime = new DateTime();
        return $dateTime->format('Ymdhis');
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

    /**
     * @return FileAdapterOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param FileAdapterOptions $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}
