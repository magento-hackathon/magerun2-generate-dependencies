<?php


namespace MagentoHackathon\Service;

use MagentoHackathon\Model\FileList;

class CheckDependency
{

    /**
     * @var GetNamespacesFromFiles
     */
    private $namespacesFromFiles;

    /**
     * CheckDependency constructor.
     * @param GetNamespacesFromFiles $namespacesFromFiles
     */
    public function __construct(GetNamespacesFromFiles $namespacesFromFiles)
    {
        $this->namespacesFromFiles = $namespacesFromFiles;
    }

    /**
     * @param $moduleName
     */
    public function execute(FileList $fileList)
    {
        $namespacesFromFiles = $this->namespacesFromFiles->execute($fileList);
        var_export($namespacesFromFiles);
    }
}
