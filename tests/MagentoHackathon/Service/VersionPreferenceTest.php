<?php


namespace MagentoHackathon\Service;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;


class VersionPreferenceTest extends TestCase
{


    /**
     * @var vfsStreamDirectory
     */
    private $root;
    /**
     * @var VersionPreference
     */
    private $service;

    public function testExecuteFileFound()
    {
        $jsonContent = '{
            "magento/module-authorization": "^100.2.3|^100.3.0",
            "magento/module-backend": "^100.2.7|^101.0.0"
        }';
        $versionPreferenceFile = vfsStream::newFile('version_preference.json');
        $versionPreferenceFile->setContent($jsonContent)->at($this->root);

        $versionPreference = $this->service->execute($versionPreferenceFile->url());
        $this->assertEquals(json_decode($jsonContent, true), $versionPreference);
    }

    /**
     * @expectedException \Exception
     */
    public function testExecuteNotFileFound()
    {
        $this->service->execute('NotFileFound');
    }

    protected function setUp()
    {
        $this->root = vfsStream::setup();
        $this->service = new VersionPreference();
    }
}