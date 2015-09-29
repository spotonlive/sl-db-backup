<?php

namespace SpotOnLive\DbBackupTest\Options;

use PHPUnit_Framework_TestCase;

class AmazonAdapterOptionsTest extends PHPUnit_Framework_TestCase
{
    /** @var \SpotOnLive\DbBackup\Options\AmazonAdapterOptions */
    protected $options;

    protected $defaults = [
        'storage_path' => '../storage/',
        'prefix' => null,
        'file_type' => 'sql',

        'credentials' => [
            'key'    => null,
            'secret' => null,
            'region' => null,
            'bucket' => null,
        ],
    ];

    protected $demoOptions = [
        'a' => 'b'
    ];

    public function setUp()
    {
        $options = new \SpotOnLive\DbBackup\Options\AmazonAdapterOptions($this->demoOptions);

        $this->options = $options;
    }

    public function testGetDefaults()
    {
        $result = $this->options->getDefaults();

        $this->assertSame($this->defaults, $result);
    }

    public function testSetDefaults()
    {
        $defaults = [
            'a' => 'b'
        ];

        $this->options->setDefaults($defaults);

        $result = $this->options->getDefaults();

        $this->assertSame($defaults, $result);
    }

    public function testGetOptions()
    {
        $options = [
            'storage_path' => '../storage/',
            'prefix' => null,
            'file_type' => 'sql',

            'credentials' => [
                'key'    => null,
                'secret' => null,
                'region' => null,
                'bucket' => null,
            ],
            'a' => 'b'
        ];

        $result = $this->options->getOptions();

        $this->assertSame($options, $result);
    }

    public function testSetOptions()
    {
        $newOptions = [
            'storage_path' => '../storage/',
            'prefix' => null,
            'file_type' => 'sql',

            'credentials' => [
                'key'    => null,
                'secret' => null,
                'region' => null,
                'bucket' => null,
            ],
        ];

        $this->options->setOptions($newOptions);

        $result = $this->options->getOptions();

        $this->assertSame($newOptions, $result);
    }

    public function testGetOfNotExistingEntry()
    {
        $key = 'non-existing';

        $result = $this->options->get($key);

        $this->assertNull($result);
    }

    public function testGet()
    {
        $key = 'a';

        $result = $this->options->get($key);

        $this->assertSame($this->demoOptions[$key], $result);
    }
}
