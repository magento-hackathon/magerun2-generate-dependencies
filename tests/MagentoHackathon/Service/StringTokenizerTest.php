<?php

namespace MagentoHackathon\Service;

use MagentoHackathon\StringTokenizer;
use PHPUnit\Framework\TestCase;

class StringTokenizerTest extends TestCase
{

    /**
     * @var StringTokenizer
     */
    private $stringTokenizer;

    /**
     *
     */
    public function testFileDontExits()
    {
        $result = $this->stringTokenizer->execute('no_exits.php');
        $this->assertEmpty($result);
    }

    protected function setUp()
    {
        $this->stringTokenizer = new StringTokenizer();
    }
}