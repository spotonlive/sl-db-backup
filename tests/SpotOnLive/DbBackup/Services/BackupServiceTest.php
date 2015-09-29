<?php

namespace SpotOnLiveTest\DbBackup\Services;

use PHPUnit_Framework_TestCase;

class BackupServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var \SpotOnLive\DbBackup\Services\BackupService */
    protected $service;

    /** @var \SpotOnLive\DbBackup\Options\BackupServiceOptions */
    protected $options;

    /** @var \SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface */
    protected $dumpAdapter;

    /** @var \SpotOnLive\DbBackup\Adapters\Backup\BackupAdapterInterface */
    protected $backupAdapter;

    protected $config = [
        'adapter_chain' => [],
        'dump' => [
            'adapter' => 'SpotOnLive\DbBackup\Adapters\Dump\MySQLDumpAdapter',
            'config' => [],
        ]
    ];

    public function setUp()
    {
        // Service
        $service = new \SpotOnLive\DbBackup\Services\BackupService($this->config);
        $this->service = $service;

        // Options
        /** @var \SpotOnLive\DbBackup\Options\BackupServiceOptions $options */
        $options = $this->getMock('SpotOnLive\DbBackup\Options\BackupServiceOptions');
        $this->options = $options;

        $service->setOptions($options);

        // Dump adapter
        /** @var \SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface $dumpAdapter */
        $dumpAdapter = $this->getMock('SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface');
        $this->dumpAdapter = $dumpAdapter;

        $service->setDumpAdapter($dumpAdapter);

        // Backup adapter
        /** @var \SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface $backupAdapter */
        $backupAdapter = $this->getMock('SpotOnLive\DbBackup\Adapters\Backup\BackupAdapterInterface');
        $this->backupAdapter = $backupAdapter;

        $service->setAdapter($backupAdapter);
    }

    public function testBackup()
    {
        $database = 'test';

        $dumpResult = 'ok';

        $this->dumpAdapter->expects($this->at(0))
            ->method('dump')
            ->with($database)
            ->willReturn($dumpResult);

        $this->backupAdapter->expects($this->at(0))
            ->method('backup')
            ->with($dumpResult);

        $result = $this->service->backup($database);

        $this->assertTrue($result);
    }

    public function testBackupNoDatabase()
    {
        $database = null;
        $environmentDatabase = 'demo-db';

        $dumpResult = 'ok';

        $this->dumpAdapter->expects($this->at(0))
            ->method('dump')
            ->with($environmentDatabase)
            ->willReturn($dumpResult);

        $this->backupAdapter->expects($this->at(0))
            ->method('backup')
            ->with($dumpResult);

        $result = $this->service->backup($database);

        $this->assertTrue($result);
    }

    public function testAttachAdaptersNoAdapter()
    {
        $method = $this->getMethod('attachAdapters');
        $obj = new $this->service($this->config);

        $adapterChain = [
            [
                'config' => [],
            ]
        ];

        $this->options->expects($this->once())
            ->method('get')
            ->with('adapter_chain')
            ->willReturn($adapterChain);


        $class = new \ReflectionClass(get_class($this->service));

        $options = $class->getMethod('setOptions');
        $options->invokeArgs($obj, [$this->options]);

        $this->setExpectedException(
            'SpotOnLive\DbBackup\Exceptions\RuntimeException',
            'Please provide an adapter class'
        );

        $method->invokeArgs($obj, []);
    }

    public function testAttachAdaptersClassNotExisting()
    {
        $method = $this->getMethod('attachAdapters');
        $obj = new $this->service($this->config);

        $adapterChain = [
            [
                'adapter' => 'InvalidClass',

                'config' => [],
            ]
        ];

        $this->options->expects($this->once())
            ->method('get')
            ->with('adapter_chain')
            ->willReturn($adapterChain);


        $class = new \ReflectionClass(get_class($this->service));

        $options = $class->getMethod('setOptions');
        $options->invokeArgs($obj, [$this->options]);

        $this->setExpectedException(
            'SpotOnLive\DbBackup\Exceptions\RuntimeException',
            'Please provide an adapter class'
        );

        $method->invokeArgs($obj, []);
    }

    public function testAttachAdaptersInvalidClass()
    {
        $method = $this->getMethod('attachAdapters');
        $obj = new $this->service($this->config);

        $adapterChain = [
            [
                'adapter' => 'stdClass',

                'config' => [],
            ]
        ];

        $this->options->expects($this->once())
            ->method('get')
            ->with('adapter_chain')
            ->willReturn($adapterChain);


        $class = new \ReflectionClass(get_class($this->service));

        $options = $class->getMethod('setOptions');
        $options->invokeArgs($obj, [$this->options]);

        $this->setExpectedException(
            'SpotOnLive\DbBackup\Exceptions\RuntimeException',
            'Please provide a valid backup adapter'
        );

        $method->invokeArgs($obj, []);
    }

    public function testAttachAdapters()
    {
        $method = $this->getMethod('attachAdapters');
        $obj = new $this->service($this->config);

        $adapterChain = [
            [
                'adapter' => 'SpotOnLive\DbBackup\Adapters\Backup\FileAdapter',

                'config' => [
                    'storage_path' => '',
                    'prefix' => 'backup_',
                    'file_type' => 'sql',
                ],
            ]
        ];

        $this->options->expects($this->once())
            ->method('get')
            ->with('adapter_chain')
            ->willReturn($adapterChain);


        $class = new \ReflectionClass(get_class($this->service));

        $options = $class->getMethod('setOptions');
        $options->invokeArgs($obj, [$this->options]);

        $result = $method->invokeArgs($obj, []);

        $this->assertTrue($result);

        $getBackupAdapterMethod = $class->getMethod('getBackupAdapter');
        $adapter = $getBackupAdapterMethod->invokeArgs($obj, []);

        $this->assertInstanceOf('SpotOnLive\DbBackup\Adapters\Backup\ChainAdapter', $adapter);
    }

    public function testAttachDumpAdapterClassNotExisting()
    {
        $method = $this->getMethod('attachDumpAdapter');
        $obj = new $this->service($this->config);

        $dumpConfig = [
        ];

        $this->options->expects($this->once())
            ->method('get')
            ->with('dump')
            ->willReturn($dumpConfig);


        $class = new \ReflectionClass(get_class($this->service));

        $options = $class->getMethod('setOptions');
        $options->invokeArgs($obj, [$this->options]);

        $this->setExpectedException(
            'SpotOnLive\DbBackup\Exceptions\RuntimeException',
            'Please provide an adapter class'
        );

        $method->invokeArgs($obj, []);
    }

    public function testDumpAdapterInvalidClass()
    {
        $method = $this->getMethod('attachDumpAdapter');
        $obj = new $this->service($this->config);

        $dumpConfig = [
            'adapter' => 'stdClass',
        ];

        $this->options->expects($this->once())
            ->method('get')
            ->with('dump')
            ->willReturn($dumpConfig);


        $class = new \ReflectionClass(get_class($this->service));

        $options = $class->getMethod('setOptions');
        $options->invokeArgs($obj, [$this->options]);

        $this->setExpectedException(
            'SpotOnLive\DbBackup\Exceptions\RuntimeException',
            'Please provide a valid adapter class'
        );

        $method->invokeArgs($obj, []);
    }

    public function testAttachDumpAdapter()
    {
        $method = $this->getMethod('attachDumpAdapter');
        $obj = new $this->service($this->config);

        $dumpConfig = [
            'adapter' => 'SpotOnLive\DbBackup\Adapters\Dump\MySQLDumpAdapter',
            'config' => [],
        ];

        $this->options->expects($this->once())
            ->method('get')
            ->with('dump')
            ->willReturn($dumpConfig);


        $class = new \ReflectionClass(get_class($this->service));

        $options = $class->getMethod('setOptions');
        $options->invokeArgs($obj, [$this->options]);

        $result = $method->invokeArgs($obj, []);

        $this->assertTrue($result);

        $getAdapterMethod = $class->getMethod('getDumpAdapter');
        $adapter = $getAdapterMethod->invokeArgs($obj, []);

        $this->assertInstanceOf('SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface', $adapter);
    }

    public function testBackupAdapter()
    {
        /** @var \SpotOnLive\DbBackup\Adapters\Backup\BackupAdapterInterface $backupAdapter */
        $backupAdapter = $this->getMock('SpotOnLive\DbBackup\Adapters\Backup\BackupAdapterInterface');
        $this->service->setAdapter($backupAdapter);

        $result = $this->service->getBackupAdapter();

        $this->assertSame($backupAdapter, $result);
    }

    public function testDumpAdapter()
    {
        /** @var \SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface $dumpAdapter */
        $dumpAdapter = $this->getMock('SpotOnLive\DbBackup\Adapters\Dump\DumpAdapterInterface');
        $this->service->setDumpAdapter($dumpAdapter);

        $result = $this->service->getDumpAdapter();

        $this->assertSame($dumpAdapter, $result);
    }

    public function testOptions()
    {
        /** @var \SpotOnLive\DbBackup\Options\BackupServiceOptions $options */
        $options = $this->getMock('SpotOnLive\DbBackup\Options\BackupServiceOptions');
        $this->service->setOptions($options);

        $result = $this->service->getOptions();

        $this->assertSame($options, $result);
    }

    /**
     * Get protected/private method from facade
     *
     * @param $name
     * @return \ReflectionMethod
     */
    protected function getMethod($name)
    {
        $class = new \ReflectionClass(get_class($this->service));

        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;

    }
}