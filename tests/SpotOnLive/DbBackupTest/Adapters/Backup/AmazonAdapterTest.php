<?php

namespace SpotOnLive\DbBackupTest\Adapters\Backup;

use PHPUnit_Framework_TestCase;

class AmazonAdapterTest extends PHPUnit_Framework_TestCase
{
    /** @var \SpotOnLive\DbBackup\adapters\Backup\AmazonAdapter */
    protected $adapter;

    /** @var \SpotOnLive\DbBackup\Options\AmazonAdapterOptions */
    protected $options;

    public function setUp()
    {
        /** @var \SpotOnLive\DbBackup\Options\AmazonAdapterOptions $options */
        $options = $this->getMock('SpotOnLive\DbBackup\Options\AmazonAdapterOptions');
        $this->options = $options;

        $adapter = new \SpotOnLive\DbBackup\Adapters\Backup\AmazonAdapter([]);
        $this->adapter = $adapter;

        $adapter->setOptions($options);
    }

    public function testGetFilename()
    {
        $prefix = 'myPrefix';
        $fileType = 'fileType';

        $this->options->expects($this->at(0))
            ->method('get')
            ->with('prefix')
            ->willReturn($prefix . '.');

        $this->options->expects($this->at(1))
            ->method('get')
            ->with('file_type')
            ->willReturn($fileType);

        $result = $this->adapter->getFilename();
        $resultArray = explode(".", $result);

        $this->assertSame($prefix, $resultArray[0]);
        $this->assertSame($fileType, $resultArray[2]);
    }

    public function testOptions()
    {
        /** @var \SpotOnLive\DbBackup\Options\AmazonAdapterOptions $options */
        $options = $this->getMock('SpotOnLive\DbBackup\Options\AmazonAdapterOptions');

        $this->adapter->setOptions($options);

        $result = $this->adapter->getOptions();

        $this->assertSame($options, $result);
    }
}
