<?php

namespace MagentoHackathon;

use MagentoHackathon\Model\FileList;
use Symfony\Component\Finder\Finder;

class FileCollector
{

    /**
     * @var array
     */
    private $includeNameList = [];

    /**
     * @var array
     */
    private $patternForExclude = [];

    /**
     * @var Finder
     */
    private $finder;
    /**
     * @var FileList
     */
    private $fileList;

    /**
     * @param Finder $finder
     * @param FileList $fileList
     * @param array|null $includeNameList
     * @param array|null $patternForExclude
     */
    public function __construct(
        Finder $finder,
        FileList $fileList,
        array $includeNameList = null,
        $patternForExclude = null
    )
    {
        $this->finder = $finder;
        $this->fileList = $fileList;

        if ($includeNameList === null) {
            $includeNameList = ['*.php', 'composer.json', '*.xml', '*.phtml'];
        }
        $this->includeNameList = $includeNameList;

        if ($patternForExclude === null) {
            $patternForExclude = ['*Test.php'];
        }
        $this->patternForExclude = $patternForExclude;
    }

    /**
     * Return Files that are relevant for decency's.
     * @param string $folder
     * @return FileList
     */
    public function getRelevantFiles(string $folder): FileList
    {
        // build the finder and file types for include
        $finder = $this->finder;
        $finder = $finder->files();
        $finder->in($folder);

        foreach ($this->includeNameList as $name) {
            $finder->name($name);
        }

        foreach ($this->patternForExclude as $notName) {
            $finder->notName($notName);
        }

        foreach ($finder->getIterator() as $file) {
            $this->fileList->addEntry($file);
        }

        return $this->fileList;
    }
}
