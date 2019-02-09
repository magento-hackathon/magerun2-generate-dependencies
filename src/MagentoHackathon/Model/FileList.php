<?php


namespace MagentoHackathon\Model;

use Symfony\Component\Finder\SplFileInfo;

/**
 * Model for different file type list.
 */
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
     * @var array
     */
    private $xmlFileList = [];

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
            case 'php':
                $this->phpFileList[] = $file;
                break;

            case 'json':
                $this->composerFile = $file;
                break;

            case 'xml':
                $this->xmlFileList[] = $file;
                break;

            default:
                $this->templates[] = $file;
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
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @return SplFileInfo[] | array
     */
    public function getXmlFileList(): array
    {
        return $this->xmlFileList;
    }
}
