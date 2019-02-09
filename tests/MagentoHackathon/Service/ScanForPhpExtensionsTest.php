<?php

namespace MagentoHackathon\Service;

use MagentoHackathon\Model\FileList;
use MagentoHackathon\StringTokenizer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class ScanForPhpExtensionsTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;
    /**
     * @var ScanForPhpExtensions
     */
    private $scanForPhpExtensions;

    public function testExecuteJsonFound()
    {

        $file1 = vfsStream::newFile('test1.php');
        $file1->at($this->root);

        $content = '
        <?php 
        $int = 12;
        echo json_encode($test = ["", "test"]);
        ';

        $file2 = vfsStream::newFile('test2.php');
        $file2->setContent($content)->at($this->root);


        $classesPathList = [$file1->url(), $file2->url()];

        $result = $this->scanForPhpExtensions->execute($classesPathList);

        $expectedResult = ['Function "json_encode" requires PHP extension "ext-json"'];
        $this->assertEquals($expectedResult, $result);
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->root = vfsStream::setup();
        $finder = new Finder();
        $fileList = new FileList();
        $this->scanForPhpExtensions = new ScanForPhpExtensions(new StringTokenizer());
    }
}