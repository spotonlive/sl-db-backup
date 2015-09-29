<?php

// @codingStandardsIgnoreStart
namespace SpotOnLive\DbBackup\Adapters\Backup {

    /**
     * Mocked file_put_contents
     *
     * @param $location
     * @param $data
     * @return bool
     */
    function file_put_contents($location, $data)
    {
        if (!$data) {
            return false;
        }

        return true;
    }

    /**
     * Mocked is_writable
     *
     * @param $directory
     * @return bool
     */
    function is_writable($directory)
    {
        if (is_null($directory)) {
            return false;
        }

        return true;
    }

    /**
     * Mock dirname
     *
     * @param $dir
     * @return mixed
     */
    function dirname($dir)
    {
        return $dir;
    }
}

namespace SpotOnLiveTest\DbBackup\Adapters\Backup {

    use PHPUnit_Framework_TestCase;
// @codingStandardsIgnoreEnd

    class FileAdapterTest extends PHPUnit_Framework_TestCase
    {
        /** @var \SpotOnLive\DbBackup\adapters\Backup\FileAdapter */
        protected $adapter;

        /** @var \SpotOnLive\DbBackup\Options\FileAdapterOptions */
        protected $options;

        public function setUp()
        {
            /** @var \SpotOnLive\DbBackup\Options\FileAdapterOptions $options */
            $options = $this->getMock('SpotOnLive\DbBackup\Options\FileAdapterOptions');
            $this->options = $options;

            $adapter = new \SpotOnLive\DbBackup\adapters\Backup\FileAdapter([]);
            $this->adapter = $adapter;

            $adapter->setOptions($options);
        }

        public function testBackupFailingToSave()
        {
            $data = null;

            $storagePath = 'abc';

            $this->options->expects($this->at(0))
                ->method('get')
                ->with('storage_path')
                ->willReturn($storagePath);

            $this->setExpectedException(
                'SpotOnLive\DbBackup\Exceptions\RuntimeException'
            );

            $this->adapter->backup($data);
        }

        public function testBackup()
        {
            $data = 'testData';

            $storagePath = 'abc';

            $this->options->expects($this->at(0))
                ->method('get')
                ->with('storage_path')
                ->willReturn($storagePath);

            $result = $this->adapter->backup($data);

            $this->assertTrue($result);
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

        public function testDate()
        {
            $dateTime = new \DateTime();

            $result = $this->adapter->getDate();
            $this->assertSame($dateTime->format('Ymdhis'), $result);
        }

        public function testGetStoragePathNotWritable()
        {
            $storagePath = null;

            $this->options->expects($this->at(0))
                ->method('get')
                ->with('storage_path')
                ->willReturn($storagePath);

            $this->setExpectedException(
                'SpotOnLive\DbBackup\Exceptions\PermissionException',
                sprintf(
                    _('Backup directory \'%s\' must be writeable'),
                    $storagePath
                )
            );

            $this->adapter->getStoragePath();
        }

        public function testGetStoragePath()
        {
            $storagePath = 'path';

            $this->options->expects($this->at(0))
                ->method('get')
                ->with('storage_path')
                ->willReturn($storagePath);

            $result = $this->adapter->getStoragePath();

            $this->assertSame($storagePath, $result);
        }

        public function testOptions()
        {
            /** @var \SpotOnLive\DbBackup\Options\FileAdapterOptions $options */
            $options = $this->getMock('SpotOnLive\DbBackup\Options\FileAdapterOptions');

            $this->adapter->setOptions($options);

            $result = $this->adapter->getOptions();

            $this->assertSame($options, $result);
        }
    }
}
