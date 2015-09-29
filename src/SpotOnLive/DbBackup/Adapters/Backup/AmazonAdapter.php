<?php

namespace SpotOnLive\DbBackup\Adapters\Backup;

use DateTime;
use SpotOnLive\DbBackup\Exceptions\PermissionException;
use SpotOnLive\DbBackup\Exceptions\RuntimeException;
use SpotOnLive\DbBackup\Options\AmazonAdapterOptions;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

class AmazonAdapter implements BackupAdapterInterface
{
    /** @var AmazonAdapterOptions */
    protected $options;

    public function __construct(array $options)
    {
        $this->options = new AmazonAdapterOptions($options);
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
        $filename = $this->getFilename();
        $configuration = $this->options->get('credentials');

        $client = new S3Client([
            'credentials' => [
                'key'    => $configuration['key'],
                'secret' => $configuration['secret'],
            ],
            'region' => $configuration['region'],
            'version' => 'latest',
        ]);

        $adapter = new AwsS3Adapter($client, $configuration['bucket']);

        $config = new \League\Flysystem\Config();

        if (!$adapter->write($this->getPath() . $filename, $data, $config)) {
            throw new RunTimeException('Please check your configuration for Amazon s3');
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
     */
    public function getPath()
    {
        $options = $this->options;
        return $options->get('storage_path');
    }
}
