<?php

namespace MagentoHackathon\Service;

use MagentoHackathon\StringTokenizer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

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

        echo bcadd(5.122, 21);   
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
        $registered =
            [
                'ext-bcmath' => '*',
                'magento/module-cookie' => '122'
            ];

        $this->scanForPhpExtensions = new ScanForPhpExtensions(new StringTokenizer(), $registered);
    }
}