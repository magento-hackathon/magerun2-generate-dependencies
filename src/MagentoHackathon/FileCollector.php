<?php

namespace MagentoHackathon;

use Symfony\Component\Finder\Finder;

class FileCollector
{

    /**
     * @var array
     */
    private $includeNameList = ['*.php', 'composer.json', '*.xml', '*.phtml'];

    /**
     * @var array
     */
    private $patternForExclude = ['*Test.php'];

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @param Finder $finder
     * @param array|null $includeNameList
     * @param array|null $patternForExclude
     */
    public function __construct(Finder $finder, array $includeNameList = null, $patternForExclude = null)
    {
        $this->finder = $finder;

        if ($includeNameList !== null) {
            $this->includeNameList = $includeNameList;
        }

        if ($patternForExclude !== null) {
            $this->patternForExclude = $patternForExclude;
        }
    }

    /**
     * Return Files that are relevant for decency's.
     * @param string $folder
     * @return array
     */
    public function getRelevantFiles(string $folder): array
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

        $files = [];
        foreach ($finder->getIterator() as $file) {
            $files[] = $file->getRealPath();
        }

        return $files;
    }
}
