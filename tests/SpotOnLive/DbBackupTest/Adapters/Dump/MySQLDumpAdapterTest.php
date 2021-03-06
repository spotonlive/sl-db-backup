<?php

// @codingStandardsIgnoreStart
namespace SpotOnLive\DbBackup\Adapters\Dump {

    function tempnam($name)
    {
        return $name;
    }

    function file_get_contents($file)
    {
        return 'demo';
    }

    function unlink($file)
    {
        return null;
    }

    function fopen($url, $permissons)
    {
        return true;
    }

    function fwrite($source, $content)
    {
        return true;
    }
}

namespace SpotOnLive\DbBackupTest\Adapters\Dump {

    use PHPUnit_Framework_TestCase;
    // @codingStandardsIgnoreEnd

    class ChainAdapterTest extends PHPUnit_Framework_TestCase
    {
        /** @var \SpotOnLive\DbBackup\adapters\Dump\MySQLDumpAdapter */
        protected $adapter;

        /** @var \SpotOnLive\DbBackup\Options\DumpOptions */
        protected $options;

        public function setUp()
        {
            /** @var \SpotOnLive\DbBackup\Options\DumpOptions $options */
            $options = $this->getMock('SpotOnLive\DbBackup\Options\DumpOptions');
            $this->options = $options;

            /** @var \SpotOnLive\DbBackup\adapters\Dump\MySQLDumpAdapter $adapter */
            $adapter = new \SpotOnLive\DbBackup\Adapters\Dump\MySQLDumpAdapter([]);
            $this->adapter = $adapter;

            $adapter->setOptions($options);
        }

        public function testDump()
        {
            $database = 'testDatabase';

            $port = 'testPort';
            $host = 'testHost';
            $username = 'testUsername';
            $password = 'testPassword';

            $this->options->expects($this->at(0))
                ->method('get')
                ->with('port')
                ->willReturn($port);

            $this->options->expects($this->at(1))
                ->method('get')
                ->with('host')
                ->willReturn($host);

            $this->options->expects($this->at(2))
                ->method('get')
                ->with('username')
                ->willReturn($username);

            $this->options->expects($this->at(3))
                ->method('get')
                ->with('password')
                ->willReturn($password);

            $this->setExpectedException(
                'SpotOnLive\DbBackup\Exceptions\RunTimeException'
            );

            $this->adapter->dump($database);
        }

        public function testOptions()
        {
            /** @var \SpotOnLive\DbBackup\Options\DumpOptions $options */
            $options = $this->getMock('SpotOnLive\DbBackup\Options\DumpOptions');
            $this->adapter->setOptions($options);

            $result = $this->adapter->getOptions();
            $this->assertSame($options, $result);
        }
    }
}
