<?php


namespace MagentoHackathon\Api;

interface GetModulePathFolderByNameInterface
{
    /**
     * @param $moduleName
     * @return mixed
     */
    public function execute($moduleName);
}
