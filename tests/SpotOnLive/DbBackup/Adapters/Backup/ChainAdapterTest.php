<?php

namespace SpotOnLiveTest\DbBackup\Adapters\Backup;

use PHPUnit_Framework_TestCase;

class ChainAdapterTest extends PHPUnit_Framework_TestCase
{
    /** @var \SpotOnLive\DbBackup\adapters\Backup\ChainAdapter */
    protected $adapter;

    /** @var array|\SpotOnLive\DbBackup\adapters\Backup\BackupAdapterInterface[] */
    protected $adapters;

    public function setUp()
    {
        $adapters = [
            $this->getMock('SpotOnLive\DbBackup\adapters\Backup\BackupAdapterInterface'),
            $this->getMock('SpotOnLive\DbBackup\adapters\Backup\BackupAdapterInterface'),
        ];

        $this->adapters = $adapters;

        /** @var \SpotOnLive\DbBackup\adapters\Backup\ChainAdapter $adapter */
        $adapter = new \SpotOnLive\DbBackup\adapters\Backup\ChainAdapter($adapters);
        $this->adapter = $adapter;
    }

    public function testBackup()
    {
        $data = 'test';

        foreach ($this->adapters as $adapter) {
            $adapter->expects($this->once())
                ->method('backup')
                ->with($data);
        }

        $result = $this->adapter->backup($data);

        $this->assertTrue($result);
    }

    public function testAdd()
    {
        /** @var \SpotOnLive\DbBackup\adapters\Backup\BackupAdapterInterface $adapter */
        $adapter = $this->getMock('SpotOnLive\DbBackup\adapters\Backup\BackupAdapterInterface');

        $result = $this->adapter->add($adapter);

        $this->assertSame($this->adapter, $result);

        // Check that the adapter has been inserted
        $adapters = $this->adapter->getAdapters();
        $this->assertSame($adapters[count($adapters) - 1], $adapter);
    }

    public function testAdapters()
    {
        $adapters = [
            $this->getMock('SpotOnLive\DbBackup\adapters\Backup\BackupAdapterInterface')
        ];

        $this->adapter->setAdapters($adapters);

        $result = $this->adapter->getAdapters();

        $this->assertSame($adapters, $result);
    }
}
