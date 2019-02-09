<?php

namespace MagentoHackathon\Service;

class ScanForPhpExtensions
{

    /**
     * @var array|null
     */
    private $phpCheckExtensions = ['json', 'xml', 'pcre', 'gd', 'bcmath'];

    /**
     * @var array
     */
    private $registeredPhpExtensions = [];

    /**
     * ScanForPhpExtensions constructor.
     * @param null $phpCheckExtensions
     * @param $registeredPhpExtensions
     */
    public function __construct($phpCheckExtensions = null, $registeredPhpExtensions = [])
    {
        if ($phpCheckExtensions !== null) {
            $this->phpExtensions = $phpCheckExtensions;
        }
        $this->registeredPhpExtensions = $registeredPhpExtensions;
    }

    public function execute(array $classes)
    {
    }
}
