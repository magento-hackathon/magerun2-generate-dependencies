<?php


namespace MagentoHackathon\Model;

use Symfony\Component\Finder\SplFileInfo;

class FileList
{
    /**
     * @var array
     */
    private $templates = [];

    /**
     * @var array
     */
    private $phpFileList = [];

    /**
     * @var string
     */
    private $composerFile = '';

    /**
     * @param SplFileInfo $file
     */
    public function addEntry(SplFileInfo $file)
    {
        switch ($file->getExtension()) {
            case '.php':
                $this->phpFileList[] = $file;
                break;

            case '.json':
                $this->composerFile = $file;
                break;

            default:
                $this->templates = $file;
                break;
        }
    }

    /**
     * @return SplFileInfo[] | array
     */
    public function getPhpFileList(): array
    {
        return $this->phpFileList;
    }

    /**
     * @return string
     */
    public function getComposerFile(): string
    {
        return $this->composerFile;
    }

    /**
     * @return SplFileInfo[] | array
     */
    public function getTemplates(array $templates): array
    {
        return $this->templates;
    }
}
