<?php

namespace MagentoHackathon\Service;

use MagentoHackathon\FileCollector;
use MagentoHackathon\Model\FileList;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class GetNamespacesFromFilesTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;
    /**
     * @var ScanForPhpExtensions
     */
    private $scanForPhpExtensions;
    /**
     * @var FileCollector
     */
    private $fileCollector;
    /**
     * @var GetNamespacesFromFiles
     */
    private $getNamespacesFromFiles;

    public function testExecuteFound()
    {

        $content = '
        <?php 
           namespace MagentoHackathon\Test\Catalog\ViewModel;
           
           use Magento\Framework\View\Element\Block\ArgumentInterface; 
           use \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory as AttributeGroupCollection;
           use Magento\Framework\Pricing\PriceCurrencyInterface;
           
           class Test 
           {
           }
        ';

        $file1 = vfsStream::newFile('test1.phtml');
        $file1->setContent($content)->at($this->root);

        $content = '
        <?php 

        echo NSname\subns\func();
        echo \NSname\subns\test();
        ';

        $file2 = vfsStream::newFile('test2.php');
        $file2->setContent($content)->at($this->root);


        $relevantFiles = $this->fileCollector->getRelevantFiles($this->root->url());
        $result = $this->getNamespacesFromFiles->execute($relevantFiles, 'MagentoHackathon');

        $this->assertCount(3, $result);
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->root = vfsStream::setup();
        $finder = new Finder();
        $fileList = new FileList();
        $this->fileCollector = new FileCollector($finder, $fileList);
        $this->getNamespacesFromFiles = new GetNamespacesFromFiles();
    }
}