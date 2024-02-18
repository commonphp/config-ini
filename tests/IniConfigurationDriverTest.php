<?php

namespace CommonPHP\Tests\Drivers\IniConfigurationDriver;

use CommonPHP\Configuration\Drivers\IniConfigurationDriver\IniConfigurationDriver;
use CommonPHP\Configuration\Exceptions\ConfigurationException;
use PHPUnit\Framework\TestCase;

/**
 * Class IniConfigurationDriverTest
 *
 * Tests for the IniConfigurationDriver class.
 */
class IniConfigurationDriverTest extends TestCase
{
    /**
     * @var IniConfigurationDriver
     */
    protected IniConfigurationDriver $driver;

    /**
     * Setting up for test case
     */
    protected function setUp(): void
    {
        $this->driver = new IniConfigurationDriver();
    }

    /**
     * Test for the `load` method
     * @throws ConfigurationException
     */
    public function testLoad()
    {
        // Test case: when the file exists and is valid INI format
        $filename = rtrim(__DIR__, '\\/').DIRECTORY_SEPARATOR.'test.ini';
        $data = $this->driver->load($filename);


        $this->assertEquals(['answerToLifeUniverseEverything' => 42], $data);

        // Test case: when the file does not exist or is invalid INI format
        $this->expectException(ConfigurationException::class);
        $filename = 'path/to/invalid/test.ini';
        $this->driver->load($filename);
    }

    /**
     * Test to verify that save method writes correctly to an ini file.
     */
    public function testSuccessfulSave(): void
    {
        $filename = rtrim(__DIR__, '\\/').DIRECTORY_SEPARATOR.'test.ini';
        $data = ['answerToLifeUniverseEverything' => 42];
        $this->driver->save($filename, $data);

        $this->assertFileExists($filename);
        $this->assertIsArray(parse_ini_file($filename, true));
    }

    /**
     * Test to verify that save method throws exception when write operation fails.
     */
    public function testSavingToFileFailure(): void
    {
        $this->expectException(ConfigurationException::class);

        $filename = '/invalid/directory/test.ini';
        $data = ['app' => ['name' => 'ApplicationName', 'version' => 1]];
        $this->driver->save($filename, $data);
    }
}