<?php

namespace SpotOnLive\DbBackupTest\Options;

use PHPUnit_Framework_TestCase;

class DumpOptionsTest extends PHPUnit_Framework_TestCase
{
    /** @var \SpotOnLive\DbBackup\Options\DumpOptions */
    protected $options;

    protected $defaults = [
    ];

    protected $demoOptions = [
        'a' => 'b'
    ];

    public function setUp()
    {
        $options = new \SpotOnLive\DbBackup\Options\DumpOptions($this->demoOptions);

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
            'a' => 'b'
        ];

        $result = $this->options->getOptions();

        $this->assertSame($options, $result);
    }

    public function testSetOptions()
    {
        $newOptions = [
            'new' => 'options'
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
